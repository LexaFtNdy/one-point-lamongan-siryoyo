<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\TrainerProfile;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'member' && !$user->memberProfile) {
            return back()->with('error', 'Lengkapi Profil Kebugaran Anda di menu Profile sebelum melakukan booking.');
        }

        $request->validate([
            'trainer_profile_id' => 'required|exists:trainer_profiles,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'shift' => 'required|string|in:Pagi,Siang,Malam',
            'message' => 'nullable|string|max:1000',
        ]);

        // Validasi Anti-Bentrok Jadwal (RAD Feature)
        $conflict = Booking::where('trainer_profile_id', $request->trainer_profile_id)
            ->where('booking_date', $request->booking_date)
            ->where('shift', $request->shift)
            ->whereIn('status', ['approved', 'pending_payment', 'waiting_verification'])
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'schedule' => 'Maaf, Trainer sudah di-booking pada tanggal dan shift tersebut. Silakan pilih waktu lain.'
            ]);
        }

        // Ambil harga PT
        $trainer = TrainerProfile::findOrFail($request->trainer_profile_id);

        Booking::create([
            'member_id' => Auth::id(),
            'trainer_profile_id' => $trainer->id,
            'booking_date' => $request->booking_date,
            'shift' => $request->shift,
            'status' => 'waiting_verification', // Langsung masuk request
            'amount' => $trainer->price_per_session,
            'message' => $request->message,
        ]);

        return redirect()->route('dashboard')->with('success', 'Request Booking berhasil dikirim ke Trainer.');
    }

    // Untuk Trainer Menyetujui/Menolak
    public function updateStatus(Request $request, Booking $booking)
    {
        // Pastikan yang login adalah PT yang bersangkutan
        if ($booking->trainerProfile->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $booking->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status booking berhasil diupdate.');
    }

    // Untuk Member Membatalkan Booking
    public function cancel(Request $request, Booking $booking)
    {
        // Pastikan yang login adalah member yang booking
        if ($booking->member_id !== Auth::id()) {
            abort(403);
        }

        // Hanya bisa cancel jika statusnya masih waiting_verification
        if ($booking->status !== 'waiting_verification') {
            return back()->with('error', 'Booking tidak dapat dibatalkan pada status ini.');
        }

        $booking->update([
            'status' => 'canceled',
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
