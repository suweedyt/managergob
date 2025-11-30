<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public const Published = 1;
    public const Unpublished = 0;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'is_published',
        'order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
}