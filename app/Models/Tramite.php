<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
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
        'category_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
