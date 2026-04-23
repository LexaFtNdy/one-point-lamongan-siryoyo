<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TrainerProfile;
use Illuminate\Support\Facades\Auth;

class TrainerProfileController extends Controller
{
    public function setup()
    {
        $profile = TrainerProfile::where('user_id', Auth::id())->first();
        return Inertia::render('Trainer/ProfileSetup', [
            'profile' => $profile
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'specialties' => 'required|array',
            'handled_experience_levels' => 'required|array',
            'coaching_style' => 'required|string',
            'available_shifts' => 'required|array',
            'price_per_session' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        TrainerProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        return redirect()->route('dashboard')->with('success', 'Parameter AI berhasil diperbarui!');
    }
}
