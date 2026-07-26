<?php

namespace Database\Seeders;

use App\Models\Bundle;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Material::factory(6)->create();

        Color::factory(6)->create();

        Size::factory(6)->create();

        Product::factory(40)->create();

        Bundle::factory(12)->create()->each(function ($bundle) {

            $products = Product::inRandomOrder()
                ->take(rand(2, 5))
                ->get();

            foreach ($products as $product) {

                $bundle->products()->attach($product->id, [
                    'quantity' => rand(1, 3)
                ]);

            }
        });
    }
}
