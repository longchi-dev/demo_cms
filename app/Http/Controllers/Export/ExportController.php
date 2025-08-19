<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Services\Export\ExportCache;
use App\Services\Export\ExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExportController extends BaseController
{
    public function __construct(
        protected ExportService $exportService,
        protected ExportCache $exportCache
    ) {}

    public function export(Request $request): JsonResponse
    {
        $uuid = $request->user()->id;
        $this->exportService->exportData($uuid);

        return $this->sendResponse(
            [
                'uuid' => $uuid,
            ],
            'Export data successfully exported.'
        );
    }

    public function getStatus(Request $request): JsonResponse
    {
        $uuid = $request->user()->id;

        $cacheData = $this->exportCache->getCache($uuid);

        return $this->sendResponse(
            [
                'status' => $cacheData
            ],
            'Status'
        );
    }
}
