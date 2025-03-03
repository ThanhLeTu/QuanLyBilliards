<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TableSeeder::class, // TableSeeder chạy trước
            CustomerSeeder::class, // CustomerSeeder chạy trước
            ServiceSeeder::class,
            ReservationSeeder::class, // ReservationSeeder chạy sau
            ReservationServiceSeeder::class,
        ]);
    }
}