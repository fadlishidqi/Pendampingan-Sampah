<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Admin KKN Wiroditan
        User::factory()->create([
            'name' => 'Admin KKN',
            'email' => 'kkn58wiroditan@gmail.com',
            'password' => Hash::make('kknundiptim58wiroditan'),
            'is_admin' => true,
        ]);
    }
}