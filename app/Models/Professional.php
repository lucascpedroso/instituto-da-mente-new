<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Professional extends Model
{
    use HasFactory, HasImages, HasSlug;

    protected string $slugFrom = 'name';

    protected string $imageField = 'photo';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'specialties' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderByDesc('is_featured')->orderBy('sort')->orderBy('name');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function whatsappNumber(): string
    {
        return $this->whatsapp ?: Setting::get('whatsapp');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
