<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/** @extends Factory<Member> */
class MemberFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'alamat' => fake()->city(),
            'telepon' => fake()->numerify('08##########'),
            'jen_kelamin' => fake()->randomElement(['L', 'P']),
            'username' => fake()->unique()->numerify('member########'),
            'password' => Hash::make(Str::random(32)),
        ];
    }
}
