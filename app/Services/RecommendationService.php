<?php

namespace App\Services;

use App\Models\TrainerProfile;

class RecommendationService
{
    public function calculateRecommendations(array $preferences)
    {
        // 1. Auto-Calculate BMI
        $heightInMeters = $preferences['height'] / 100;
        $bmi = $preferences['weight'] / ($heightInMeters * $heightInMeters);
        
        $isObese = $bmi >= 30;
        $isUnderweight = $bmi < 18.5;

        // 2. Fetch Base Query
        $query = TrainerProfile::with(['user' => function($q) {
            $q->select('id', 'name', 'gender', 'email');
        }]);

        if ($preferences['gender_preference'] !== 'no_preference') {
            $query->whereHas('user', function($q) use ($preferences) {
                $q->where('gender', $preferences['gender_preference']);
            });
        }

        $trainers = $query->get();

        // 3. Multi-Dimensional Scoring
        $trainers->map(function ($trainer) use ($preferences, $isObese, $isUnderweight) {
            $score = 0;
            $specialties = $trainer->specialties ?? [];
            
            // --- DIMENSI FISIK & TUJUAN (Bobot 30%) ---
            // Cek Goal Dasar
            if (in_array($preferences['goal'], $specialties)) {
                $score += 15; 
            }
            // Auto-BMI Logic: Jika Obesitas & PT ahli Obesity Management, tambah poin besar
            if ($isObese && in_array('Obesity Management', $specialties)) {
                $score += 15;
            }
            // Auto-BMI Logic: Jika Underweight & PT ahli Bulking, tambah poin besar
            if ($isUnderweight && in_array('Bulking', $specialties)) {
                $score += 15;
            }

            // --- DIMENSI MEDIS / SAFETY (Bobot 25%) ---
            if ($preferences['injury_history'] !== 'None') {
                // Jika cedera tapi PT pakai gaya ekstrem (CrossFit/Powerlifting), KURANGI POIN (Penalti)
                if (in_array('CrossFit', $specialties) || in_array('Powerlifting', $specialties)) {
                    $score -= 20; 
                }
                // Jika PT ahli Rehabilitasi / Fisioterapi, TAMBAH POIN MAKSIMAL
                if (in_array('Rehabilitation', $specialties) || in_array('Physiotherapy', $specialties)) {
                    $score += 25;
                }
            } else {
                // Kalau sehat, kasih nilai default 25
                $score += 25;
            }

            // --- DIMENSI LOGISTIK: SHIFT & BUDGET (Bobot 25%) ---
            // Shift Match (10%)
            $shifts = $trainer->available_shifts ?? [];
            if (in_array($preferences['available_shift'], $shifts)) {
                $score += 10;
            }

            // Budget Match (15%)
            if ($trainer->price_per_session <= $preferences['max_budget']) {
                $score += 15;
            } else {
                // Penalti kecil jika sedikit over budget, penalti besar jika jauh over budget
                $diff = $trainer->price_per_session - $preferences['max_budget'];
                if ($diff <= 50000) {
                    $score += 5; // Masih bisa ditoleransi
                }
            }

            // --- DIMENSI PSIKOLOGIS & PENGALAMAN (Bobot 20%) ---
            // Coaching Style (10%)
            if ($trainer->coaching_style === $preferences['coaching_style']) {
                $score += 10;
            }
            // Experience Level (10%)
            $expLevels = $trainer->handled_experience_levels ?? [];
            if (in_array($preferences['experience_level'], $expLevels)) {
                $score += 10;
            }

            // Normalisasi agar skor maksimal 100% dan tidak negatif
            if ($score < 0) $score = 0;
            if ($score > 100) $score = 100;

            $trainer->match_score = $score;
            return $trainer;
        });

        // 4. Pisahkan Exact Match (Skor tinggi + Filter Ketat)
        // Jika preferensi gender diset, maka semua $trainers di atas sudah difilter by gender (exact match).
        $exactMatches = $trainers->sortByDesc('match_score')->values();

        $alternatives = collect();

        // 5. FUZZY MATCHING: Jika Exact Match kosong, cari alternatif (abaikan gender)
        if ($exactMatches->isEmpty() && $preferences['gender_preference'] !== 'no_preference') {
            $allTrainers = TrainerProfile::with(['user' => function($q) {
                $q->select('id', 'name', 'gender', 'email');
            }])->get();

            $alternatives = $allTrainers->map(function ($trainer) use ($preferences, $isObese, $isUnderweight) {
                $score = 0;
                $specialties = $trainer->specialties ?? [];
                
                // Fisik & Tujuan
                if (in_array($preferences['goal'], $specialties)) $score += 15; 
                if ($isObese && in_array('Obesity Management', $specialties)) $score += 15;
                if ($isUnderweight && in_array('Bulking', $specialties)) $score += 15;

                // Medis
                if ($preferences['injury_history'] !== 'None') {
                    if (in_array('CrossFit', $specialties) || in_array('Powerlifting', $specialties)) $score -= 20; 
                    if (in_array('Rehabilitation', $specialties) || in_array('Physiotherapy', $specialties)) $score += 25;
                } else {
                    $score += 25;
                }

                // Logistik
                $shifts = $trainer->available_shifts ?? [];
                if (in_array($preferences['available_shift'], $shifts)) $score += 10;

                if ($trainer->price_per_session <= $preferences['max_budget']) {
                    $score += 15;
                } else {
                    $diff = $trainer->price_per_session - $preferences['max_budget'];
                    if ($diff <= 50000) $score += 5;
                }

                // Psikologis
                if ($trainer->coaching_style === $preferences['coaching_style']) $score += 10;
                $expLevels = $trainer->handled_experience_levels ?? [];
                if (in_array($preferences['experience_level'], $expLevels)) $score += 10;

                // Penalty untuk gender yang berbeda (karena dia masuk fuzzy match)
                $score -= 10;

                if ($score < 0) $score = 0;
                if ($score > 100) $score = 100;

                $trainer->match_score = $score;
                return $trainer;
            })->sortByDesc('match_score')->values();
        }

        return [
            'exact_matches' => $exactMatches,
            'alternatives' => $alternatives
        ];
    }
}
