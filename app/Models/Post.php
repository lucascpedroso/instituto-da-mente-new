<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, HasImages, HasSlug;

    public const STATUSES = [
        'rascunho' => 'Rascunho',
        'publicado' => 'Publicado',
        'agendado' => 'Agendado',
    ];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if ($post->status === 'publicado' && ! $post->published_at) {
                $post->published_at = now();
            }
        });
    }

    /**
     * Posts visíveis: publicados, ou agendados cuja data já chegou.
     * O agendamento funciona sem cron — basta a data de publicação.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereIn('status', ['publicado', 'agendado'])
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Professional::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function readingTime(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->body)) / 200));
    }

    public function summary(): string
    {
        return $this->excerpt ?: Str::limit(trim(strip_tags((string) $this->body)), 180);
    }
}
