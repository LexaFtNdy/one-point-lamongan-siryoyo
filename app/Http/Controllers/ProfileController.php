<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        
        // Eager load necessary profiles
        $user->load(['memberProfile', 'trainerProfile.achievements']);

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'userProfile' => $user->role === 'trainer' ? $user->trainerProfile : $user->memberProfile,
            'tab' => $request->query('tab', 'account'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Update the user's fitness profile.
     */
    public function updateMemberProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'height' => 'required|numeric|min:100|max:250',
            'weight' => 'required|numeric|min:30|max:200',
            'injury_history' => 'nullable|string',
        ]);

        $user = $request->user();

        \App\Models\MemberProfile::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return Redirect::route('profile.edit', ['tab' => 'kebugaran']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
