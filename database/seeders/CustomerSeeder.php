<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::insert([
            [
                'name' => 'Mario Rossi',
                'email' => 'mario.rossi@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Giulia Verdi',
                'email' => 'giulia.verdi@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Luca Bianchi',
                'email' => 'luca.bianchi@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
