<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AdminSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'logo_full' => ['sometimes', 'image', 'mimes:jpeg,png,svg', 'max:2048'],
            'logo_mini' => ['sometimes', 'image', 'mimes:jpeg,png,svg', 'max:2048'],
        ];
    }
}
