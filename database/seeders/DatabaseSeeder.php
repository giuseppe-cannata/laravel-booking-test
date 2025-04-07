<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Chiamato il seeder per creare utenti di test
        $this->call([
            TestUserSeeder::class,
            BookingSeeder::class,
            CustomOrderSeeder::class,
        ]);
    }
}
