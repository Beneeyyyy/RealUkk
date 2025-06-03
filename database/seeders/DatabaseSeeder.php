<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Industri;
use App\Models\Pkl;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // 1. Guru
        Guru::factory()->count(10)->create();

        // 4. Industri
        Industri::factory()->count(10)->create();

        // 3. Siswa (butuh industri dan guru ids)
       

       Siswa::factory()->count(10)->create();

        // 2. Pkl (butuh siswa id)
        $siswaIds = Siswa::pluck('id')->toArray();
         $industriIds = Industri::pluck('id')->toArray();
        $guruIds = Guru::pluck('id')->toArray();

        Pkl::factory()->count(10)->create([
            'siswa_id' => $faker->randomElement($siswaIds),
            'industri_id' => $faker->randomElement($industriIds),
            'guru_id' => $faker->randomElement($guruIds),
        ]);
    }
}
