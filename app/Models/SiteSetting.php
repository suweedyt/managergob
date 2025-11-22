<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'header_height',
        'header_background_color',
        'header_logo',
        'footer_background_color',
        'footer_text_color',
        'footer_contact',
        'footer_socials',
        'footer_copy',
    ];

    protected $casts = [
        'footer_socials' => 'array',
    ];
}