<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
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
    public function definition()
    {
        return [
            'username' => fake()->unique()->userName(),
            'firstname' => fake()->name(),
            'image' => fake()->imageUrl(),
            'lastname' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'status' => fake()->boolean(),
            'referral_id' => fake()->numberBetween(1, 100),
            'email_verified' => fake()->boolean(),
            'sms_verified' => fake()->boolean(),
            'kyc_verified' => fake()->boolean(),
            // 'two_factor_status' =>fake()->boolean(),
            'two_factor_verified' => fake()->boolean(),
            // 'accept' =>fake()->boolean(),
            'email_verified_at' => now(),
            'password' => Hash::make('rokondev'), // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
