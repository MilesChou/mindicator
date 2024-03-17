<?php

namespace App\Models\Vcs;

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
class Tags extends Model
{
    use HasFactory;

    protected function commit(): HasOne
    {
        return $this->hasOne(Commits::class, 'id', 'repository_id');
    }

    protected function repository(): HasOne
    {
        return $this->hasOne(Repositories::class, 'id', 'repository_id');
    }
}
