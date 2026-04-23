<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private function guardAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
    }

    public function dashboard()
    {
        $this->guardAdmin();

        $totalMembers = User::where('role', 'member')->count();
        $totalTrainers = User::where('role', 'trainer')->count();
        $totalBookings = Booking::count();

        $bookingsByStatus = [
            'waiting' => Booking::where('status', 'waiting_verification')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'rejected' => Booking::where('status', 'rejected')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
        ];

        $recentBookings = Booking::with(['member', 'trainerProfile.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalMembers' => $totalMembers,
                'totalTrainers' => $totalTrainers,
                'totalBookings' => $totalBookings,
                'bookingsByStatus' => $bookingsByStatus,
            ],
            'recentBookings' => $recentBookings,
        ]);
    }

    public function users()
    {
        $this->guardAdmin();

        $users = User::where('role', '!=', 'admin')
            ->withCount(['memberProfile', 'trainerProfile'])
            ->withTrashed()
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Users', [
            'users' => $users,
        ]);
    }

    public function suspendUser(User $user)
    {
        $this->guardAdmin();

        if ($user->role === 'admin') {
            abort(403, 'Cannot suspend admin.');
        }

        if ($user->trashed()) {
            $user->restore();
            return back()->with('success', "Akun {$user->name} berhasil diaktifkan kembali.");
        }

        $user->delete();
        return back()->with('success', "Akun {$user->name} berhasil di-suspend.");
    }

    public function activityLog()
    {
        $this->guardAdmin();

        $activities = Booking::with([
            'member',
            'trainerProfile.user'
        ])->orderBy('created_at', 'desc')->get();

        return Inertia::render('Admin/ActivityLog', [
            'activities' => $activities
        ]);
    }
}
