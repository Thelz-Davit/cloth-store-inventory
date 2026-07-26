<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Demo Admin
        User::create([
            'username' => 'Admin Demo',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // Demo Staff
        User::create([
            'username' => 'Staff Demo',
            'email' => 'staff@demo.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Random users
        User::factory(8)->create();

        $this->call([
            InventorySeeder::class,
            InboundSeeder::class,
            OutboundSeeder::class,
        ]);
    }
}