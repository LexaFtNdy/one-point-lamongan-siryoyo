<?php

namespace App\Http\Controllers;

use App\Models\TrainerProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrainerPublicProfileController extends Controller
{
    public function show(Request $request, TrainerProfile $trainerProfile)
    {
        if ($request->user()->role !== 'member') {
            abort(403);
        }

        $trainerProfile->load([
            'user:id,name,gender',
            'achievements' => function ($query) {
                $query->where('status', 'approved')
                    ->where('visibility', 'public')
                    ->select([
                        'id',
                        'trainer_profile_id',
                        'category',
                        'title',
                        'issuer',
                        'credential_id',
                        'verification_url',
                        'description',
                        'achieved_at',
                        'expires_at',
                        'certificate_path',
                        'certificate_mime',
                        'visibility',
                        'status',
                        'created_at',
                    ])
                    ->latest();
            },
        ]);

        return Inertia::render('Member/TrainerProfile', [
            'trainer' => $trainerProfile,
        ]);
    }
}
