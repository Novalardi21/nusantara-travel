<?php

namespace Database\Factories;

use App\Models\OperatorLoket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/** @extends Factory<OperatorLoket> */
class OperatorLoketFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'user' => fake()->unique()->numerify('loket########'),
            'password' => Hash::make(Str::random(32)),
        ];
    }
}
