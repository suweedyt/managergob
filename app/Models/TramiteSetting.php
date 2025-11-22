<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TramiteSetting extends Model
{
    protected $table = 'tramite_settings';

    protected $fillable = [
        'title',
        'subtitle',
    ];
}
