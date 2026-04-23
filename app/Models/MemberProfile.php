<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'goal',
        'gender_preference',
        'experience_level',
        'height',
        'weight',
        'injury_history',
        'coaching_style',
        'available_shift',
        'max_budget',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
