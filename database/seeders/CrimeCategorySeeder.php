<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CrimeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Illegal Logging', 'description' => 'Unauthorized cutting of trees'],
            ['name' => 'Wetland Encroachment', 'description' => 'Building on or draining wetlands'],
            ['name' => 'Illegal Mining', 'description' => 'Mining without permits'],
            ['name' => 'Illegal Dumping', 'description' => 'Dumping waste in prohibited areas'],
            ['name' => 'Water Pollution', 'description' => 'Contaminating water bodies'],
            ['name' => 'Air Pollution', 'description' => 'Illegal emission of pollutants'],
            ['name' => 'Wildlife Trafficking', 'description' => 'Illegal trade of wild animals'],
        ];
        foreach ($categories as $cat) {
            \App\Models\CrimeCategory::create($cat);
        }
        echo "Crime categories seeded successfully\n";
    }
}
