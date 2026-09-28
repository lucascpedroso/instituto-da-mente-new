<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory, HasImages, HasSlug;

    public const FORMATS = [
        'presencial' => 'Presencial',
        'online' => 'Online',
        'hibrido' => 'Presencial e online',
    ];

    public const STATUSES = [
        'publicado' => 'Publicado',
        'rascunho' => 'Rascunho',
    ];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['is_flagship' => 'boolean'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'publicado')->orderByDesc('is_flagship')->orderBy('sort')->orderBy('title');
    }

    public function formatLabel(): string
    {
        return self::FORMATS[$this->format] ?? $this->format;
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
