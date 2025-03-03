<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Table::create([
            'table_number' => 1, // Đảm bảo table_number là 1
            'status' => 'available',
            'area' => 'Sảnh chung',
            'table_type' => 'Bàn thường',
            'price' => 80000,
            'description' => 'Bàn thường ở khu vực sảnh chung'
        ]);

        // Thêm các bàn khác nếu muốn
    }
}