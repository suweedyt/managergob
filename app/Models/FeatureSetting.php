<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureSetting extends Model
{
    protected $table = 'feature_settings';

    protected $fillable = [
        'title',
        'subtitle',
        'button_text',
        'button_color',
        'background_image',
        'link_type',
        'button_url',
        'slug',
        'landing_content',
        'landing_image',
    ];
}
