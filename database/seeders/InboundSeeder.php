<?php

namespace Database\Seeders;


use App\Models\Inbound;

use App\Models\Product;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InboundSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for ($i = 0; $i < 150; $i++) {

            Inbound::create([

                'product_id' => Product::inRandomOrder()->first()->id,

                'user_id' => 1,

                'quantity' => rand(5, 25),

                'inbound_date' => fake()->dateTimeBetween('-12 months')->format('Y-m-d'),

            ]);

        }
    }
}
