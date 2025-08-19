<?php

namespace App\Services\File;

class CsvFileService
{
    public function __construct() {}

    public function createCsvFile(string $uuid): string
    {
        $filePath = storage_path('app/public/' . config('export.path') . '/' . $uuid . '.csv');

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        return $filePath;
    }
}
