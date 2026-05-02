<?php

namespace App\Http\Requests\Auth\Section;

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
            'title_full' => ['required', 'string', 'max:255'],
            'title_short' => ['nullable', 'string', 'max:100'],
            'logo_class' => ['nullable', 'string', 'max:255'],
            'logo_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:4096'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'mode' => ['required', 'string', 'in:content,link'],
            'redirect_url' => ['nullable','string','max:2048','required_if:mode,link','url'],
            'is_published' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
