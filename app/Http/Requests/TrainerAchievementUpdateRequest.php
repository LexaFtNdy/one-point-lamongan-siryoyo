<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainerAchievementUpdateRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category' => ['required', 'in:certification,award,education,license,seminar,other'],
            'title' => ['required', 'string', 'max:120'],
            'issuer' => ['required', 'string', 'max:120'],
            'credential_id' => ['nullable', 'string', 'max:120'],
            'verification_url' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'achieved_at' => ['required', 'date', 'before_or_equal:today'],
            'expires_at' => ['nullable', 'date', 'after:achieved_at'],
            'visibility' => ['required', 'in:public,private'],
            'certificate' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ];
    }
}
