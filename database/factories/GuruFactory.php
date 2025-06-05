<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guru>
 */
class GuruFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      return [
            'name' => $this->faker->name(),
            'nip' => $this->faker->unique()->numerify('##########'),
            'gender' => $this->faker->randomElement(['L', 'P']),
            'alamat' => $this->faker->address(),
            'kontak' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->userName() . '@guru.com',
            'password' => Hash::make('password123'), // default password
        ];
    }
}
