<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    public const KINDS = [
        'paciente' => 'Paciente',
        'aluno' => 'Aluno(a)',
    ];

    public const STATUSES = [
        'pendente' => 'Pendente',
        'aprovado' => 'Aprovado',
        'rejeitado' => 'Rejeitado',
    ];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'consent_at' => 'datetime',
        ];
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'aprovado')->orderByDesc('is_featured')->orderBy('sort')->latest();
    }

    public function kindLabel(): string
    {
        return self::KINDS[$this->kind] ?? $this->kind;
    }
}
