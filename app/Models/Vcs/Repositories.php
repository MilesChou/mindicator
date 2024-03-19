<?php

namespace App\Models\Vcs;

use App\Vcs\Git\ReferenceInterface;
use Composer\Pcre\Preg;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RuntimeException;

/**
 * @property int $id
 * @property string $url
 *
 * @property Collection|Commits[] $commits
 * @property Collection|Tags[] $tags
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

    protected function commits(): HasMany
    {
        return $this->hasMany(Tags::class, 'repository_id', 'id');
    }

    protected function tags(): HasMany
    {
        return $this->hasMany(Tags::class, 'repository_id', 'id');
    }

    public function cacheDir(?string $path = null): string
    {
        $cacheDir = Preg::replace('{[^a-z0-9.]}i', '-', $this->url);

        if ($path === null) {
            return $cacheDir;
        }

        return $cacheDir . '/' . ltrim($path, '/');
    }

    public function findRef(string $ref): ReferenceInterface
    {
        /** @var Tags $tag */
        $tag = $this->tags->firstWhere('name', $ref);

        if ($tag) {
            return $tag;
        }

        /** @var Commits $commit */
        $commit = $this->commits->firstWhere('sha1', $ref);

        if ($commit) {
            return $commit;
        }

        throw new RuntimeException('Cannot find ref: ' . $ref);
    }
}
