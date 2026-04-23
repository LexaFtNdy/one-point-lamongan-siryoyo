<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin
        User::updateOrCreate(
            ['email' => 'admin@onepoint.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'gender' => 'no_preference',
            ]
        );

        // 2. Members
        User::updateOrCreate(
            ['email' => 'member@onepoint.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'member',
                'gender' => 'male',
            ]
        );

        User::updateOrCreate(
            ['email' => 'siti.member@onepoint.com'],
            [
                'name' => 'Siti Aminah',
                'password' => Hash::make('password'),
                'role' => 'member',
                'gender' => 'female',
            ]
        );

        // 3. Trainers
        $trainers = [
            ['name' => 'Agus PT', 'email' => 'agus.pt@onepoint.com', 'gender' => 'male'],
            ['name' => 'Reza PT', 'email' => 'reza.pt@onepoint.com', 'gender' => 'male'],
            ['name' => 'Dinda PT', 'email' => 'dinda.pt@onepoint.com', 'gender' => 'female'],
            ['name' => 'Rina PT', 'email' => 'rina.pt@onepoint.com', 'gender' => 'female'],
        ];

        foreach ($trainers as $trainer) {
            User::updateOrCreate(
                ['email' => $trainer['email']],
                [
                    'name' => $trainer['name'],
                    'password' => Hash::make('password'),
                    'role' => 'trainer',
                    'gender' => $trainer['gender'],
                ]
            );
        }
    }
}
