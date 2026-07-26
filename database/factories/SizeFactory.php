<?php

namespace Database\Factories;

use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Size>
 */
class SizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Size::class;
    public function definition(): array
    {

        return [
            'size_name' => fake()->unique()->randomElement([
                'XS',
                'S',
                'M',
                'L',
                'XL',
                'XXL'
            ]),
        ];
    }
}
