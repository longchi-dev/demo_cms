<?php

namespace App\Services\File;

class CsvFileService
{
    public function __construct() {}

    public function createCsvFile(string $uuid): string
    {
        $filePath = base_path(config('export.csv_path') . '/' . $uuid . '.csv');

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        
        return $filePath;
    }
}
