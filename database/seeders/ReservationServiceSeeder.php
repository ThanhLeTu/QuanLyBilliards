<?php

namespace Database\Seeders;

use App\Models\ReservationService;
use App\Models\Reservation;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ReservationServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reservation1 = Reservation::find(1);
        $service1 = Service::where('name', 'Bia Hà Nội')->firstOrFail();
        $service2 = Service::where('name', 'Nước ngọt Coca-Cola')->firstOrFail();

        ReservationService::create([
            'reservation_id' => $reservation1->id,
            'service_id' => $service1->id,
            'quantity' => 5
        ]);

        ReservationService::create([
            'reservation_id' => $reservation1->id,
            'service_id' => $service2->id,
            'quantity' => 2
        ]);

        // Thêm các reservation services khác nếu muốn
    }
}