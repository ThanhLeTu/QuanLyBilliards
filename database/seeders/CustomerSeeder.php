<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'name' => 'Nguyễn Văn An',
            'phone' => '0912345678',
            'email' => 'an.nguyen@example.com',
        ]);

        // Thêm các khách hàng khác nếu muốn
    }
}