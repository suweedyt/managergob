<?php

namespace App\Http\Requests\Auth\FeatureSetting;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:512'],
            'button_text' => ['nullable', 'string', 'max:120'],
            'button_color' => ['nullable', 'string', 'max:20', 'regex:/^#?[0-9a-fA-F]{3,6}$/'],
            'background_image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
