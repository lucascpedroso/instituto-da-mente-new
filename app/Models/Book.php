<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory, HasImages, HasSlug;

    public const STATUSES = [
        'publicado' => 'Publicado',
        'rascunho' => 'Rascunho',
        'esgotado' => 'Esgotado',
    ];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    /** Livros visíveis no site (publicados e esgotados). */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->whereIn('status', ['publicado', 'esgotado'])->orderByDesc('is_featured')->orderBy('sort')->orderBy('title');
    }

    public function isSoldOut(): bool
    {
        return $this->status === 'esgotado';
    }

    public function formattedPrice(): ?string
    {
        return $this->price !== null ? 'R$ '.number_format((float) $this->price, 2, ',', '.') : null;
    }
}
