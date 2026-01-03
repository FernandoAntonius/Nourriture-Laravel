<?php

namespace Database\Seeders;

use App\Models\AgeClassification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgeClassificationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AgeClassification::create([
            'name' => 'Balita',
            'min_age' => 0,
            'max_age' => 5,
            'description' => 'Usia 0-5 tahun',
        ]);

        AgeClassification::create([
            'name' => 'Anak-anak',
            'min_age' => 6,
            'max_age' => 12,
            'description' => 'Usia 6-12 tahun',
        ]);

        AgeClassification::create([
            'name' => 'Remaja',
            'min_age' => 13,
            'max_age' => 17,
            'description' => 'Usia 13-17 tahun',
        ]);

        AgeClassification::create([
            'name' => 'Dewasa Muda',
            'min_age' => 18,
            'max_age' => 30,
            'description' => 'Usia 18-30 tahun',
        ]);

        AgeClassification::create([
            'name' => 'Dewasa',
            'min_age' => 31,
            'max_age' => 45,
            'description' => 'Usia 31-45 tahun',
        ]);

        AgeClassification::create([
            'name' => 'Setengah Baya',
            'min_age' => 46,
            'max_age' => 60,
            'description' => 'Usia 46-60 tahun',
        ]);

        AgeClassification::create([
            'name' => 'Lansia',
            'min_age' => 61,
            'max_age' => 150,
            'description' => 'Usia 61+ tahun',
        ]);
    }
}
