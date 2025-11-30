<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'google_maps_api_key',
    ];
}