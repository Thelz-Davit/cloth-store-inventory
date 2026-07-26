<?php

namespace Database\Seeders;


use App\Models\Bundle;


use App\Models\Outbound;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OutboundSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {

            Outbound::create([

                'bundle_id' => Bundle::inRandomOrder()->first()->id,

                'user_id' => 1,

                'quantity' => rand(1, 5),

                'outbound_date' => fake()->dateTimeBetween('-12 months')->format('Y-m-d'),

                'status' => 'completed',

            ]);

        }
    }
}
