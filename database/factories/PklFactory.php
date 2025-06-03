<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pkl>
 */
class PklFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       $mulai = $this->faker->dateTimeBetween('-1 month', 'now');
    $selesai = $this->faker->dateTimeBetween($mulai, '+1 month');

    return [
        'siswa_id' => null,
        'industri_id' => null,
        'guru_id' => null,
        'mulai' => $mulai->format('Y-m-d'),
        'selesai' => $selesai->format('Y-m-d'),
    ];
    }
}
