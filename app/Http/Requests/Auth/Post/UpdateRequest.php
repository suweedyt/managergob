<?php

namespace App\Http\Requests\Auth\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:2', 'max:50', 'string'],
            'category' => ['required'],
            'is_published' => ['required'],
            // file not required on update
            'file' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:min_width=500, min_height=500'],
            'is_slider' => ['nullable', 'boolean'],
            'is_news_slider' => ['nullable', 'boolean'],
            'slider_file' => ['sometimes', 'required_if:is_slider,1', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:min_width=500, min_height=500'],
            'slider_position_x' => ['nullable', 'integer', 'min:0', 'max:100'],
            'slider_position_y' => ['nullable', 'integer', 'min:0', 'max:100'],
            'description' => ['required', 'min:10', 'max:5000000']
        ];
    }
}
