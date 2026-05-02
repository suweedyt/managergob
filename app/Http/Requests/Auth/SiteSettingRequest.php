<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'header_height' => ['required', 'integer', 'between:40,160'],
            'header_background_color' => ['required', 'regex:/^#([A-Fa-f0-9]{3}){1,2}$/'],
            'header_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            'footer_background_color' => ['required', 'regex:/^#([A-Fa-f0-9]{3}){1,2}$/'],
            'footer_text_color' => ['required', 'regex:/^#([A-Fa-f0-9]{3}){1,2}$/'],
            'footer_contact' => ['nullable', 'string'],
            'footer_socials' => ['nullable', 'array'],
            'footer_socials.*.name' => ['required_with:footer_socials', 'string', 'max:50'],
            'footer_socials.*.url' => ['required_with:footer_socials', 'url'],
            'footer_socials.*.icon_url' => ['required_with:footer_socials', 'url'],
            'footer_copy' => ['nullable', 'string', 'max:255'],
            'footer_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            'footer_map_iframe' => ['nullable', 'string'],
            'footer_links' => ['nullable', 'array'],
            'footer_links.*.name' => ['required_with:footer_links', 'string', 'max:100'],
            'footer_links.*.url' => ['required_with:footer_links', 'url'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $socials = $this->input('footer_socials', []);
        $links = $this->input('footer_links', []);

        if (!is_array($socials)) {
            $socials = [];
        }

        $normalized = [];

        foreach ($socials as $index => $social) {
            if (!is_array($social)) {
                $normalized[$index] = $social;
                continue;
            }

            $url = isset($social['url']) ? trim((string) $social['url']) : '';
            if ($url !== '') {
                if (preg_match('#^//#', $url)) {
                    $social['url'] = 'https:' . $url;
                } elseif (!preg_match('#^[a-z][a-z0-9+\-.]*://#i', $url)) {
                    $social['url'] = 'https://' . ltrim($url, '/');
                }
            }

            $iconUrl = isset($social['icon_url']) ? trim((string) $social['icon_url']) : '';
            if ($iconUrl !== '') {
                if (preg_match('#^//#', $iconUrl)) {
                    $social['icon_url'] = 'https:' . $iconUrl;
                } elseif (!preg_match('#^[a-z][a-z0-9+\-.]*://#i', $iconUrl)) {
                    $social['icon_url'] = 'https://' . ltrim($iconUrl, '/');
                }
            }

            $normalized[$index] = $social;
        }

        $normalizedLinks = [];
        if (is_array($links)) {
            foreach ($links as $i => $link) {
                if (!is_array($link)) {
                    $normalizedLinks[$i] = $link;
                    continue;
                }
                $url = isset($link['url']) ? trim((string) $link['url']) : '';
                if ($url !== '') {
                    if (preg_match('#^//#', $url)) {
                        $link['url'] = 'https:' . $url;
                    } elseif (!preg_match('#^[a-z][a-z0-9+\-.]*://#i', $url)) {
                        $link['url'] = 'https://' . ltrim($url, '/');
                    }
                }
                $normalizedLinks[$i] = $link;
            }
        }

        $this->merge([
            'footer_socials' => $normalized,
            'footer_links' => $normalizedLinks,
        ]);
    }

    public function messages(): array
    {
        return [
            'footer_socials.*.name.required_with' => 'Cada red social debe incluir un nombre.',
            'footer_socials.*.url.required_with' => 'Cada red social debe incluir una URL.',
            'footer_socials.*.url.url' => 'Verifica que la URL de la red social tenga un formato válido (ejemplo: https://ejemplo.com).',
            'footer_socials.*.icon_url.required_with' => 'Agrega la URL del icono para cada red social.',
            'footer_socials.*.icon_url.url' => 'La URL del icono debe comenzar con http:// o https://.',
            'footer_text_color.required' => 'Selecciona un color de texto para el footer.',
            'footer_text_color.regex' => 'El color de texto debe ser un código HEX válido.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $footerSocials = $this->input('footer_socials', []);
            
            foreach ($footerSocials as $index => $social) {
                $name = trim($social['name'] ?? '');
                $url = trim($social['url'] ?? '');
                $iconUrl = trim($social['icon_url'] ?? '');

                if ($name === '' && $url === '' && $iconUrl === '') {
                    continue;
                }

                if ($name === '' || $url === '' || $iconUrl === '') {
                    $validator->errors()->add("footer_socials.$index", 'Cada red social debe incluir nombre, URL y el enlace del icono.');
                }
            }

            $links = $this->input('footer_links', []);
            foreach ($links as $index => $link) {
                $name = trim($link['name'] ?? '');
                $url = trim($link['url'] ?? '');

                if ($name === '' && $url === '') {
                    continue;
                }

                if ($name === '' || $url === '') {
                    $validator->errors()->add("footer_links.$index", 'Cada enlace de interés debe tener nombre y URL.');
                }
            }
        });
    }
}
