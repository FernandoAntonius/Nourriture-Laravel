<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = base_path('nutrients_csvfile.csv');
        
        if (!file_exists($csvFile)) {
            $this->command->error('CSV file not found at ' . $csvFile);
            return;
        }

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file); // Skip header row

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 9) continue;

            // Parse CSV values
            $name = trim($row[0] ?? '');
            $measure = trim($row[1] ?? '');
            $grams = $this->parseFloat($row[2] ?? 0);
            $calories = $this->parseFloat($row[3] ?? 0);
            $protein = $this->parseFloat($row[4] ?? 0);
            $fat = $this->parseFloat($row[5] ?? 0);
            $sat_fat = $this->parseFloat($row[6] ?? 0);
            $fiber = $this->parseFloat($row[7] ?? 0);
            $carbs = $this->parseFloat($row[8] ?? 0);

            // Skip empty or invalid rows
            if (empty($name) || $name === 'Food') continue;

            // Check if food already exists
            if (Food::where('name', $name)->exists()) {
                continue;
            }

            // Create food record
            Food::create([
                'name' => $name,
                'measure' => $measure,
                'grams' => $grams,
                'calories' => $calories,
                'protein' => $protein,
                'fat' => $fat,
                'sat_fat' => $sat_fat,
                'fiber' => $fiber,
                'carbs' => $carbs,
            ]);

            $this->command->info("Created food: {$name}");
        }

        fclose($file);
        $this->command->info('Food import completed!');
    }

    /**
     * Helper function to parse float values, handling 't' (trace) as 0
     */
    private function parseFloat($value): float
    {
        $value = trim($value);
        
        // Handle 't' (trace) and empty values as 0
        if ($value === 't' || $value === '' || $value === 'a') {
            return 0;
        }

        $float = floatval($value);
        return $float >= 0 ? $float : 0;
    }
}
