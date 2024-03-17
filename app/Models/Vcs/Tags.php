<?php

namespace App\Models\Vcs;

use App\Vcs\Git\ReferenceInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property string $repository_id
 * @property string $commit_sha1
 *
 * @property Repositories $repository
 * @property Commits $commit
 */
class Tags extends Model implements ReferenceInterface
{
    use HasFactory;

    protected $fillable = [
        'name',
        'repository_id',
        'commit_sha1',
        'labels',
    ];

    protected function casts(): array
    {
        return [
            'labels' => 'array',
        ];
    }

    protected function commit(): HasOne
    {
        return $this->hasOne(Commits::class, 'id', 'repository_id');
    }

    protected function repository(): HasOne
    {
        return $this->hasOne(Repositories::class, 'id', 'repository_id');
    }

    public function ref(): string
    {
        return $this->name;
    }

    public function cloverFile(): string
    {
        return storage_path('app/metrics') . '/'
            . $this->repository->cacheDir() . '/'
            . $this->ref() . '/'
            . 'clover.xml';
    }
}
