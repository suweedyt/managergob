<?php

namespace App\Http\Requests\Auth\Banner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:180'],
            'long_description' => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'button_bg_color' => ['nullable', 'string', 'max:20'],
            'button_url' => ['nullable', 'url'],
            // max: 102400 = 100MB (value in kilobytes)
            'media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4', 'max:102400'],
            'media_url' => ['nullable', 'url'],
            'media_type' => ['required', 'in:image,video'],
            'position_x' => ['nullable', 'integer', 'min:0', 'max:100'],
            'position_y' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }
}
