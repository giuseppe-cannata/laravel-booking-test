<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'pippo@example.com';
        $password = 'password';

        if (User::where('email', $email)->exists()) {
            $this->command->warn("L'utente con email $email esiste già.");
            return;
        }

        User::create([
            'name' => 'Utente Test',
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        $this->command->info("Utente di test creato:");
        $this->command->info("Email: $email");
        $this->command->info("Password: password");
    }
}

