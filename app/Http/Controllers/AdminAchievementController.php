<?php

namespace App\Http\Controllers;

use App\Models\TrainerAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminAchievementController extends Controller
{
    private function guardAdmin(): void
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $this->guardAdmin();

        $query = TrainerAchievement::with([
            'trainerProfile.user:id,name,gender',
            'reviewer:id,name'
        ])
            ->orderByRaw("FIELD(status, 'pending', 'rejected', 'approved')")
            ->orderBy('created_at', 'desc');

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $achievements = $query->paginate(15)->withQueryString();

        $pendingCount = TrainerAchievement::where('status', 'pending')->count();

        return Inertia::render('Admin/Achievements', [
            'achievements' => $achievements,
            'pendingCount' => $pendingCount,
            'filters' => [
                'status' => $request->status,
            ],
        ]);
    }

    public function approve(TrainerAchievement $achievement)
    {
        $this->guardAdmin();

        $achievement->update([
            'status' => 'approved',
            'reject_reason' => null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Kredensial berhasil disetujui.');
    }

    public function reject(Request $request, TrainerAchievement $achievement)
    {
        $this->guardAdmin();

        $validated = $request->validate([
            'reject_reason' => ['required', 'string', 'max:500'],
        ]);

        $achievement->update([
            'status' => 'rejected',
            'reject_reason' => $validated['reject_reason'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Kredensial ditolak dengan alasan.');
    }
}
