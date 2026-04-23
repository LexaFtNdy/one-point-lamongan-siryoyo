<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'trainer_profile_id',
        'booking_date',
        'shift',
        'status',
        'amount',
        'payment_proof_path',
        'rejection_reason',
        'message',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function trainerProfile()
    {
        return $this->belongsTo(TrainerProfile::class);
    }
}
