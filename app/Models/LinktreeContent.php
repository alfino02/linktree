<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinktreeContent extends Model
{
    protected $fillable = [
        'site',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }
}
