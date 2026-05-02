<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsShowcaseItem extends Model
{
    protected $table = 'news_showcase_items';

    protected $fillable = [
        'post_id',
        'is_large',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
