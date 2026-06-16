<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@nema.go.ug'],
            [
                'name' => 'NEMA Admin',
                'password' => Hash::make('Admin@12345'),
                'role' => 'admin',
                'phone_number' => '+256700000000',
                'is_active' => true,
            ]
        );

        // Seed default officers
        User::updateOrCreate(
            ['email' => 'officer1@nema.go.ug'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('Officer@12345'),
                'role' => 'officer',
                'phone_number' => '+256711111111',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'officer2@nema.go.ug'],
            [
                'name' => 'Sarah Nabakooza',
                'password' => Hash::make('Officer@12345'),
                'role' => 'officer',
                'phone_number' => '+256722222222',
                'is_active' => true,
            ]
        );

        $this->command?->info('Admin and Officer users seeded successfully.');
    }
}
