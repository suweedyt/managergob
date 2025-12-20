<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_full',
        'title_short',
        'logo_class',
        'logo_image',
        'description',
        'content',
        'mode',
        'redirect_url',
        'is_published',
        'order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'order' => 'integer',
    ];
}
