<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {  // 1️⃣ Buat user baru
        $user = User::create([
            'name' => 'Asep Surya',
            'email' => 'asepsurya1998@gmail.com',
            'password' => Hash::make('asepsurya1998'), // password terenkripsi
        ]);

        // 2️⃣ Generate Token secara otomatis
        $user->tokens()->create([
            'token' => Str::random(12), // Token 60 karakter
            'expires_at' => null, // Expired 7 hari ke depan
            // 'expires_at' => now()->addDays(7), // Expired 7 hari ke depan
        ]);

    }
}
