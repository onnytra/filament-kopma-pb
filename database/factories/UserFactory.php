<?php

namespace Database\Factories;

use App\Models\Jabatan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nia' => $this->faker->unique()->numerify('#######'),
            'name' => 'User',
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->numerify('##########'),
            'password' => 'password123',
            'photo' => null,
            'status_user' => true,
            'jabatan_id' => Jabatan::first()->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
