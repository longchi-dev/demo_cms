<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Services\Export\ExportService;
use Illuminate\Http\Request;

class ExportController extends BaseController
{
    public function __construct(
        protected ExportService $exportService,
    ) {}

    public function export(Request $request)
    {
        // $this->exportService->exportData();
    }
}
