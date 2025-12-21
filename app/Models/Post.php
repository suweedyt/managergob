<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public const Published = 1;
    public const Unpublished = 0;

    protected $fillable = [
        'title',
        'gallery_id',
        'category_id',
        'is_published',
        'file',
        'is_slider',
        'is_news_slider',
        'slider_gallery_id',
        'slider_position_x',
        'slider_position_y',
        'description',
        'banner_short_description',
    ];

    public function gallery() {
        return $this->belongsTo(Gallery::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function sliderGallery() {
        return $this->belongsTo(Gallery::class, 'slider_gallery_id');
    }

    public function newsShowcaseItem()
    {
        return $this->hasOne(NewsShowcaseItem::class, 'post_id');
    }
}
