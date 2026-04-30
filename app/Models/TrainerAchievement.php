<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class TrainerAchievement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
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
        'reject_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'achieved_at' => 'date',
        'expires_at' => 'date',
        'reviewed_at' => 'datetime',
    ];

    protected $hidden = [
        'certificate_path',
    ];

    protected $appends = [
        'certificate_url',
    ];

    public function trainerProfile()
    {
        return $this->belongsTo(TrainerProfile::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getCertificateUrlAttribute(): ?string
    {
        if (!$this->certificate_path) {
            return null;
        }

        return Storage::disk('public')->url($this->certificate_path);
    }
}
