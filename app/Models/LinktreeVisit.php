<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinktreeVisit extends Model
{
    protected $fillable = [
        'site',
        'visit_date',
        'time_wib',
        'vid',
        'device',
        'source',
        'country',
        'city',
        'path',
        'visited_at_ms',
    ];
}
