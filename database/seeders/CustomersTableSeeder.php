<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        for ($i = 1; $i <= 10000; $i++) {
            $data[] = [
                'id' => (string) Str::uuid(),
                'name' => 'Customer ' . $i,
                'email' => 'customer' . $i . '@example.com',
            ];
        }

        // Chèn dữ liệu vào bảng customers
        DB::table('customers')->insert($data);
    }
}
