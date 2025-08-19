<?php

namespace App\Services\Export;
use Illuminate\Support\Facades\Cache;

class ExportCache
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCESS = 'success';

    private function key(string $uuid): string
    {
        return sprintf(config('export.cache_key'), $uuid);
    }

    private function ttl(): \Illuminate\Support\Carbon
    {
        return now()->addMinutes(config('export.ttl', 60));
    }

    public function init(string $uuid): void
    {
        Cache::put(
            $this->key($uuid),
            [
                'status' => static::STATUS_PENDING,
                'path' => null,
                'total' => 0,
            ],
            $this->ttl()
        );
    }

    public function start(string $uuid, int $currentTotal): void
    {
        $data = $this->getCache($uuid) ?? [
            'status' => static::STATUS_PENDING,
            'path'   => null,
            'total'  => 0,
        ];

        $data['status'] = static::STATUS_PROCESSING;

        if ($currentTotal > 0) {
            $data['total'] = $currentTotal;
        }

        Cache::put(
            $this->key($uuid),
            $data,
            $this->ttl()
        );
    }

    public function done(string $uuid, string $path, int $total): void
    {
        $data = $this->getCache($uuid) ?? [
            'status' => static::STATUS_PROCESSING,
            'path' => null,
            'total' => 0,
        ];

        $data['status'] = static::STATUS_SUCCESS;
        $data['total'] = $total;
        $data['path'] = $path;

        Cache::put(
            $this->key($uuid),
            $data,
            $this->ttl()
        );
    }

    public function getCache(string $uuid)
    {
        return Cache::get($this->key($uuid));
    }

}
