<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainerAchievementStoreRequest;
use App\Http\Requests\TrainerAchievementUpdateRequest;
use App\Models\TrainerAchievement;
use App\Models\TrainerProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TrainerAchievementController extends Controller
{
    public function store(TrainerAchievementStoreRequest $request)
    {
        $user = $request->user();
        if ($user->role !== 'trainer') {
            abort(403);
        }

        $profile = TrainerProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            return back()->with('error', 'Lengkapi profil personal trainer terlebih dahulu.');
        }

        DB::transaction(function () use ($request, $profile) {
            $currentCount = TrainerAchievement::where('trainer_profile_id', $profile->id)
                ->lockForUpdate()
                ->count();

            if ($currentCount >= 3) {
                throw ValidationException::withMessages([
                    'limit' => 'Maksimal 3 sertifikasi/pencapaian per personal trainer.'
                ]);
            }

            $data = $request->validated();
            $data['status'] = 'pending';
            $data['reviewed_by'] = null;
            $data['reviewed_at'] = null;

            if ($request->hasFile('certificate')) {
                $path = $request->file('certificate')->store("trainer-achievements/{$profile->id}", 'public');
                $data['certificate_path'] = $path;
                $data['certificate_mime'] = $request->file('certificate')->getClientMimeType();
            }

            $profile->achievements()->create($data);
        });

        return redirect()->route('profile.edit', ['tab' => 'achievements'])->with('success', 'Kredensial profesional berhasil dikirim untuk verifikasi admin.');
    }

    public function update(TrainerAchievementUpdateRequest $request, TrainerAchievement $achievement)
    {
        $user = $request->user();
        if ($user->role !== 'trainer') {
            abort(403);
        }

        $achievement->loadMissing('trainerProfile');
        if ($achievement->trainerProfile->user_id !== $user->id) {
            abort(403);
        }

        $data = $request->validated();
        $data['status'] = 'pending';
        $data['reviewed_by'] = null;
        $data['reviewed_at'] = null;

        if ($request->hasFile('certificate')) {
            if ($achievement->certificate_path) {
                Storage::disk('public')->delete($achievement->certificate_path);
            }

            $path = $request->file('certificate')->store("trainer-achievements/{$achievement->trainer_profile_id}", 'public');
            $data['certificate_path'] = $path;
            $data['certificate_mime'] = $request->file('certificate')->getClientMimeType();
            $data['status'] = 'pending';
            $data['reviewed_by'] = null;
            $data['reviewed_at'] = null;
        }

        $achievement->update($data);

        return redirect()->route('profile.edit', ['tab' => 'achievements'])->with('success', 'Kredensial profesional berhasil diperbarui dan menunggu verifikasi admin.');
    }

    public function destroy(TrainerAchievement $achievement)
    {
        $user = request()->user();
        if ($user->role !== 'trainer') {
            abort(403);
        }

        $achievement->loadMissing('trainerProfile');
        if ($achievement->trainerProfile->user_id !== $user->id) {
            abort(403);
        }

        if ($achievement->certificate_path) {
            Storage::disk('public')->delete($achievement->certificate_path);
        }

        $achievement->delete();

        return redirect()->route('profile.edit', ['tab' => 'achievements'])->with('success', 'Kredensial profesional berhasil dihapus.');
    }
}
