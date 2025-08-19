<?php

namespace App\Services\Export;

use App\Repositories\Export\ExportRepository;
use App\Services\File\CsvFileService;
use App\Jobs\ExportBatchJob;

class ExportService
{
    protected int $batchSize;

    public function __construct(
        protected ExportRepository $exportRepository,
        protected CsvFileService $csvFileService,
        protected ExportCache $exportCache
    ) {
        $this->batchSize = config('export.batch_size');
    }

    public function exportData(string $uuid): string
    {
        $filePath = $this->csvFileService->createCsvFile($uuid);

        $this->exportCache->init($uuid);

        ExportBatchJob::dispatch(
            $uuid,
            $filePath,
            0,
            $this->batchSize
        );

        return $filePath;
    }
}
