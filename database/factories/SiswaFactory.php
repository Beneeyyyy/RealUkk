<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'nama' => $this->faker->name(),
            'nis' => $this->faker->unique()->numerify('########'),
            'gender' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'alamat' => $this->faker->address(),
            'kontak' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'gambar' => 'default.jpg', // bisa ganti sesuai kebutuhan
            'password' => Hash::make('password123'),
            'status_pkl' => $this->default['status_pkl'] ?? false, // default status PKL
           
        ];
    }
}
