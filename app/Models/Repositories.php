<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $url
 */
class Repositories extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }
}
