<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $repository_id
 * @property string $sha1
 * @property array $labels
 * @property Repositories $repository
 */
class Commits extends Model
{
    use HasFactory;

    protected $fillable = [
        'sha1',
        'repository_id',
        'labels',
    ];

    protected function casts(): array
    {
        return [
            'labels' => 'array',
        ];
    }

    protected function repository(): HasOne
    {
        return $this->hasOne(Repositories::class, 'id', 'repository_id');
    }

    public function cloverFile(): string
    {
        return storage_path('app/metrics') . '/'
            . $this->repository->cacheDir() . '/'
            . $this->sha1 . '/'
            . 'clover.xml';
    }
}
