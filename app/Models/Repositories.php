<?php

namespace App\Models;

use Composer\Pcre\Preg;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
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
            'labels' => 'array',
        ];
    }

    public function cacheDir(?string $path = null): string
    {
        $cacheDir = Preg::replace('{[^a-z0-9.]}i', '-', $this->url);

        if ($path === null) {
            return $cacheDir;
        }

        return $cacheDir . '/' . ltrim($path, '/');
    }
}
