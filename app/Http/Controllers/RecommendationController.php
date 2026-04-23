<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\RecommendationService;
use App\Models\MemberProfile;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'member' && !$user->memberProfile) {
            return redirect()->route('profile.edit', ['tab' => 'kebugaran'])
                ->with('error', 'Lengkapi Profil Kebugaran Anda agar Algoritma dapat memberikan rekomendasi yang akurat.');
        }

        return Inertia::render('Member/FindTrainer');
    }

    public function calculate(Request $request, RecommendationService $service)
    {
        $user = Auth::user();
        if (!$user->memberProfile) {
            return redirect()->route('profile.edit', ['tab' => 'kebugaran'])
                ->with('error', 'Lengkapi Profil Kebugaran Anda agar Algoritma dapat memberikan rekomendasi yang akurat.');
        }

        $validated = $request->validate([
            'goal' => 'required|string',
            'gender_preference' => 'required|string|in:male,female,no_preference',
            'experience_level' => 'required|string',
            'coaching_style' => 'required|string',
            'available_shift' => 'required|string',
            'max_budget' => 'required|numeric|min:0',
        ]);

        // Gabungkan data input dengan data profil member yang statis
        $inputData = array_merge($validated, [
            'height' => $user->memberProfile->height,
            'weight' => $user->memberProfile->weight,
            'injury_history' => $user->memberProfile->injury_history ?? 'None',
        ]);

        // MemberProfile sudah dikelola di menu Profile terpisah,
        // sehingga kita langsung bisa melakukan kalkulasi rekomendasi.

        $trainers = $service->calculateRecommendations($inputData);

        return Inertia::render('Member/Catalog', [
            'exact_matches' => $trainers['exact_matches'],
            'alternatives' => $trainers['alternatives'],
            'filters' => $validated,
        ]);
    }
}
