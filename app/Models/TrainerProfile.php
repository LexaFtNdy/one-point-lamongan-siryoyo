<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialties',
        'handled_experience_levels',
        'coaching_style',
        'available_shifts',
        'price_per_session',
        'description',
    ];

    protected $casts = [
        'specialties' => 'array',
        'handled_experience_levels' => 'array',
        'available_shifts' => 'array',
        'price_per_session' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
