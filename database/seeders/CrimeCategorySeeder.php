<?php

namespace Database\Seeders;

use App\Models\Crime;
use Illuminate\Database\Seeder;

class CrimeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds the CRIME table with common environmental crime categories.
     */
    public function run(): void
    {
        $crimes = [
            ['category_name' => 'Illegal Logging',       'description' => 'Unauthorized cutting of trees',          'severity_level' => 'High'],
            ['category_name' => 'Wetland Encroachment',  'description' => 'Building on or draining wetlands',       'severity_level' => 'High'],
            ['category_name' => 'Illegal Mining',        'description' => 'Mining without permits',                 'severity_level' => 'Critical'],
            ['category_name' => 'Illegal Dumping',       'description' => 'Dumping waste in prohibited areas',      'severity_level' => 'Medium'],
            ['category_name' => 'Water Pollution',       'description' => 'Contaminating water bodies',             'severity_level' => 'High'],
            ['category_name' => 'Air Pollution',         'description' => 'Illegal emission of pollutants',         'severity_level' => 'Medium'],
            ['category_name' => 'Wildlife Trafficking',  'description' => 'Illegal trade of wild animals',          'severity_level' => 'Critical'],
        ];

        foreach ($crimes as $crime) {
            Crime::create($crime);
        }

        $this->command?->info('Crime records seeded successfully.');
    }
}
