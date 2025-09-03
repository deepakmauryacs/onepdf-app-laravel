<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $first = fake()->firstName();
        $last  = fake()->lastName();
        return [
            'use_id' => str_pad((string) fake()->numberBetween(0, 99999999), 8, '0', STR_PAD_LEFT)
                       . str_pad((string) fake()->numberBetween(0, 99999999), 8, '0', STR_PAD_LEFT),
            'first_name' => $first,
            'last_name' => $last,
            'country' => fake()->country(),
            'company' => fake()->company(),
            'name' => "$first $last",
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= 'password',
            'agreed_terms' => true,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
