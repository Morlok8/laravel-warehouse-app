<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'customer' => fake()->name(),
            //'created_at' => rand(50, 100000),
            'warehouse_id' => \App\Models\Warehouse::inRandomOrder()->first()->id,
            'status' => "active",
        ];
    }
}
