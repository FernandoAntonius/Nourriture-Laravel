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
            'name' => '0~2',
            'min_age' => 0,
            'max_age' => 2,
            'description' => 'Usia 0-2 tahun',
        ]);

        AgeClassification::create([
            'name' => '3~9',
            'min_age' => 3,
            'max_age' => 9,
            'description' => 'Usia 3-9 tahun',
        ]);

        AgeClassification::create([
            'name' => '10~19',
            'min_age' => 10,
            'max_age' => 19,
            'description' => 'Usia 10-19 tahun',
        ]);

        AgeClassification::create([
            'name' => '20~29',
            'min_age' => 20,
            'max_age' => 29,
            'description' => 'Usia 20-29 tahun',
        ]);

        AgeClassification::create([
            'name' => '30~39',
            'min_age' => 30,
            'max_age' => 39,
            'description' => 'Usia 30-39 tahun',
        ]);

        AgeClassification::create([
            'name' => '40~49',
            'min_age' => 40,
            'max_age' => 49,
            'description' => 'Usia 40-49 tahun',
        ]);

        AgeClassification::create([
            'name' => '50~59',
            'min_age' => 50,
            'max_age' => 59,
            'description' => 'Usia 50-59 tahun',
        ]);

        AgeClassification::create([
            'name' => '60~69',
            'min_age' => 60,
            'max_age' => 69,
            'description' => 'Usia 60-69 tahun',
        ]);

        AgeClassification::create([
            'name' => '70+',
            'min_age' => 70,
            'max_age' => 150,
            'description' => 'Usia 70+ tahun',
        ]);
    }
}
