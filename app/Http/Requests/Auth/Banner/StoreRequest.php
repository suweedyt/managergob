<?php

namespace App\Http\Requests\Auth\Banner;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            // if admin provides a button text, button_url must be present and a valid URL
            'button_url' => ['nullable', 'required_with:button_text', 'url'],
            // max: 102400 = 100MB (value in kilobytes)
            'media' => ['required_without:media_url', 'file', 'mimes:jpg,jpeg,png,webp,mp4', 'max:102400'],
            'media_url' => ['nullable', 'url'],
            'media_type' => ['required', 'in:image,video'],
            'position_x' => ['nullable', 'integer', 'min:0', 'max:100'],
            'position_y' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }
}
