<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Rimuovi tutte le prenotazioni esistenti (opzionale)
        DB::table('bookings')->truncate();

        // Crea 10 prenotazioni usando la factory
        \App\Models\Booking::factory(10)->create();
    }
}
