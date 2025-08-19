<?php

namespace App\Repositories\Export;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class ExportRepository
{
    public function getDataWithOffsetAndLimit(int $offset, int $limit): Collection
    {
        return DB::table('customers') 
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function getAllDataCount(): int
    {
        return DB::table('customers')->count(); 
    }
}
