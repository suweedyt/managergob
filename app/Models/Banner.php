<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'long_description',
        'button_text',
        'button_bg_color',
        'button_url',
        'media_path',
        'media_type',
        'position_x',
        'position_y',
        'is_published',
    ];

    protected function mediaPath(): Attribute
    {
        return Attribute::make(
            get: fn ($path) => $path ? (Str::startsWith($path, ['http://', 'https://']) ? $path : '/images/banners/' . ltrim($path, '/\\')) : null
        );
    }
}
