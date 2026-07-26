<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            'product_name' => fake()->randomElement([
                'Kaos Boi',
                'Hoodie Naruto',
                'Jaket Kakasih',
                'Celana Hitam',
                'Topi Yankees',
                'Kemeja Flannel',
                'Sweater Off-White'
            ]),

            'material_id' => \App\Models\Material::inRandomOrder()->first()?->id,
            'color_id' => \App\Models\Color::inRandomOrder()->first()?->id,
            'size_id' => \App\Models\Size::inRandomOrder()->first()?->id,

            'stock' => fake()->numberBetween(0, 100),

            'status' => true,
        ];
    }
}
