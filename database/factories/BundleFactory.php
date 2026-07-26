<?php

namespace Database\Factories;

use App\Models\Bundle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bundle>
 */
class BundleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Bundle::class;
    public function definition(): array
    {
        return [
            'bundle_name' => fake()->randomElement([
                'Paket Kaos Polos',
                'Paket Hoodie',
                'Paket Seragam',
                'Paket Jersey',
                'Paket Casual',
                'Paket Premium',
                'Paket Hemat',
                'Paket Anak',
                'Paket Dewasa',
                'Paket Komunitas',
            ]),
        ];
    }
}
