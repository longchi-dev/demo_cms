<?php

namespace App\Jobs;

use App\Repositories\Export\ExportRepository;
use App\Services\Export\ExportCache;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;

class ExportBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, Batchable;

    protected ExportCache $exportCache;

    public function __construct(
        protected string $uuid,
        protected string $filePath,
        protected int $offset,
        protected int $limit
    ) {
        $this->exportCache = new ExportCache();
    }

    public function handle(): void
    {
        $data = app(ExportRepository::class)->getDataWithOffsetAndLimit(
            $this->offset,
            $this->limit
        );

        if (empty($data)) {
            return;
        }

        $handle = fopen($this->filePath, 'a');

        foreach ($data as $row) {
            $rowArray = (array) $row;
            fputcsv($handle, $rowArray);
        }

        fclose($handle);

        $currentTotal = count($data) + $this->offset;

        $this->exportCache->start(
            $this->uuid,
            $currentTotal
        );

        $total = app(ExportRepository::class)->getAllDataCount();

        if ($this->offset + $this->limit < $total) {
            ExportBatchJob::dispatch(
                $this->uuid,
                $this->filePath,
                $this->offset + $this->limit,
                $this->limit
            );
        } else {
            $url = asset("storage/csv/{$this->uuid}.csv");
            $this->exportCache->done(
                $this->uuid,
                $url,
                $currentTotal
            );
        }
    }
}
