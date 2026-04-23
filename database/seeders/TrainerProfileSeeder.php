<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TrainerProfile;

class TrainerProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainers = User::where('role', 'trainer')->get();

        $profiles = [
            'agus.pt@onepoint.com' => [
                'specialties' => ['Weight Loss', 'Cardio', 'Endurance', 'Obesity Management'],
                'handled_experience_levels' => ['Beginner', 'Intermediate'],
                'coaching_style' => 'Motivator',
                'available_shifts' => ['Pagi', 'Siang'],
                'price_per_session' => 120000,
                'description' => 'Halo! Saya Agus, PT berpengalaman yang ramah dan suportif. Spesialisasi saya adalah penurunan berat badan ekstrem (Obesity Management) dan peningkatan stamina.',
            ],
            'reza.pt@onepoint.com' => [
                'specialties' => ['Muscle Gain', 'Powerlifting', 'Bodybuilding', 'Bulking'],
                'handled_experience_levels' => ['Intermediate', 'Advanced'],
                'coaching_style' => 'Drill Sergeant',
                'available_shifts' => ['Siang', 'Malam'],
                'price_per_session' => 200000,
                'description' => 'Reza di sini. Saya percaya pada disiplin keras. Spesialis saya adalah pembentukan otot (Bulking) dan angkat beban ekstrem. Tidak cocok untuk pemula yang gampang menyerah.',
            ],
            'dinda.pt@onepoint.com' => [
                'specialties' => ['Flexibility', 'Yoga', 'Core Strength', 'Rehabilitation'],
                'handled_experience_levels' => ['Beginner', 'Intermediate', 'Advanced'],
                'coaching_style' => 'Educator',
                'available_shifts' => ['Pagi', 'Siang', 'Malam'],
                'price_per_session' => 175000,
                'description' => 'Saya Dinda. Saya melatih dengan pendekatan ilmiah dan edukatif. Sangat cocok untuk kamu yang fokus pada kekuatan inti atau sedang dalam masa rehabilitasi cedera.',
            ],
            'rina.pt@onepoint.com' => [
                'specialties' => ['Rehabilitation', 'Mobility', 'Weight Loss', 'Physiotherapy'],
                'handled_experience_levels' => ['Beginner'],
                'coaching_style' => 'Educator',
                'available_shifts' => ['Pagi', 'Malam'],
                'price_per_session' => 150000,
                'description' => 'Saya Rina, spesialis pemulihan cedera dan mobilitas. Jika kamu punya riwayat cedera lutut atau punggung, saya akan memastikan latihanmu 100% aman.',
            ],
        ];

        foreach ($trainers as $trainer) {
            if (isset($profiles[$trainer->email])) {
                TrainerProfile::updateOrCreate(
                    ['user_id' => $trainer->id],
                    $profiles[$trainer->email]
                );
            }
        }
    }
}
