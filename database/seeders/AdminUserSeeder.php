<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Officer;
use App\Models\Stuff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Admin account
        $adminStuff = Stuff::updateOrCreate(
            ['email' => 'admin@nema.go.ug'],
            [
                'password' => Hash::make('admin123'),
                'role'     => 'Admin',
                'is_active' => true,
            ]
        );
        Admin::updateOrCreate(
            ['stuff_id' => $adminStuff->stuff_id],
            ['full_name' => 'NEMA Admin']
        );

        // Seed Officer accounts
        $officer1Stuff = Stuff::updateOrCreate(
            ['email' => 'officer1@nema.go.ug'],
            [
                'password'  => Hash::make('officer1'),
                'role'      => 'Officer',
                'is_active' => true,
            ]
        );
        Officer::updateOrCreate(
            ['stuff_id' => $officer1Stuff->stuff_id],
            ['full_name' => 'John Doe']
        );

        $officer2Stuff = Stuff::updateOrCreate(
            ['email' => 'officer2@nema.go.ug'],
            [
                'password'  => Hash::make('officer1'),
                'role'      => 'Officer',
                'is_active' => true,
            ]
        );
        Officer::updateOrCreate(
            ['stuff_id' => $officer2Stuff->stuff_id],
            ['full_name' => 'Sarah Nabakooza']
        );

        $this->command?->info('Admin and Officer users seeded successfully (stuff + profile tables).');
    }
}
