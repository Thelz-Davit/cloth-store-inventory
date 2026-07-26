<?php

namespace Database\Factories;

use App\Models\Inbound;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inbound>
 */

class InboundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Inbound::class;
    public function definition(): array
    {
        return [
            //
        ];
    }
}
