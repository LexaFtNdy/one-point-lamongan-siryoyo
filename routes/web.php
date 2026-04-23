<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TrainerProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $user = $request->user();

    if ($user->role === 'admin') {
        return app(AdminController::class)->dashboard();
    }

    if ($user->role === 'trainer') {
        $profile = \App\Models\TrainerProfile::where('user_id', $user->id)->first();
        $bookings = [];
        if ($profile) {
            $bookings = \App\Models\Booking::with('member.memberProfile')
                ->where('trainer_profile_id', $profile->id)
                ->whereIn('status', ['waiting_verification', 'approved'])
                ->orderBy('booking_date', 'asc')
                ->get();
        }
        return Inertia::render('Trainer/Dashboard', [
            'bookings' => $bookings,
            'hasProfile' => !!$profile
        ]);
    }

    // Member
    $bookings = \App\Models\Booking::with('trainerProfile.user')
        ->where('member_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();
    return Inertia::render('Dashboard', [
        'bookings' => $bookings
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/member', [ProfileController::class, 'updateMemberProfile'])->name('member.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Member Routes
    Route::get('/find-pt', [RecommendationController::class, 'index'])->name('member.find-pt');
    Route::post('/find-pt/results', [RecommendationController::class, 'calculate'])->name('member.find-pt.results');
    Route::get('/find-pt/results', function () {
        return redirect()->route('member.find-pt');
    });
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::patch('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/member/bookings', function (\Illuminate\Http\Request $request) {
        $bookings = \App\Models\Booking::with('trainerProfile.user')
            ->where('member_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return Inertia::render('Member/MyBookings', [
            'bookings' => $bookings,
        ]);
    })->name('member.bookings');

    // Trainer Routes
    Route::get('/trainer/setup', function() {
        return redirect()->route('profile.edit', ['tab' => 'parameter']);
    })->name('trainer.setup');
    Route::post('/trainer/setup', [TrainerProfileController::class, 'update'])->name('trainer.setup.update');
    Route::patch('/booking/{booking}/status', [BookingController::class, 'updateStatus'])->name('booking.update-status');
    Route::get('/trainer/history', function (\Illuminate\Http\Request $request) {
        $user = $request->user();
        $profile = \App\Models\TrainerProfile::where('user_id', $user->id)->first();
        $bookings = [];
        if ($profile) {
            $bookings = \App\Models\Booking::with('member.memberProfile')
                ->where('trainer_profile_id', $profile->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        return Inertia::render('Trainer/History', [
            'bookings' => $bookings,
        ]);
    })->name('trainer.history');

    // Admin Routes
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::patch('/admin/users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('admin.suspend-user');
    Route::get('/admin/activity-log', [AdminController::class, 'activityLog'])->name('admin.activity-log');
});

require __DIR__.'/auth.php';
