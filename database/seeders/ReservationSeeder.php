<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table1 = Table::where('table_number', 1)->firstOrFail();
        $customer1 = Customer::create(['name' => 'Nguyễn Văn A', 'phone' => '0901234567']);

        Reservation::create([
            'table_id' => $table1->id,
            'customer_id' => $customer1->id,
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'status' => 'playing'
        ]);

        // Thêm các đặt bàn khác nếu muốn
    }
}