<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
            'name' => 'Bia Hà Nội',
            'price' => 25000,
            'description' => 'Bia Hà Nội chai 330ml'
        ]);

        Service::create([
            'name' => 'Nước ngọt Coca-Cola',
            'price' => 15000,
            'description' => 'Nước ngọt Coca-Cola lon 330ml'
        ]);

        Service::create([
            'name' => 'Thuê gậy cơ bản',
            'price' => 30000,
            'description' => 'Thuê gậy cơ bản cho 1 giờ chơi'
        ]);

        // Thêm các dịch vụ khác nếu muốn
    }
}