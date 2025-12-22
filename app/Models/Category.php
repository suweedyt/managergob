<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'position',
    ];

    public function tramites()
    {
        return $this->hasMany(Tramite::class, 'category_id');
    }
}
