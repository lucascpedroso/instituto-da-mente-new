<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use Prunable;

    public const TYPES = [
        'contato' => 'Contato',
        'agendamento' => 'Agendamento',
        'inscricao_curso' => 'Inscrição em curso',
    ];

    public const STATUSES = [
        'novo' => 'Novo',
        'em_atendimento' => 'Em atendimento',
        'concluido' => 'Concluído',
    ];

    public const MODALITIES = [
        'presencial' => 'Presencial',
        'online' => 'Online',
        'indiferente' => 'Tanto faz',
    ];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['consent_at' => 'datetime'];
    }

    /** Retenção LGPD: contatos sem atualização há mais de 2 anos são excluídos. */
    public function prunable(): Builder
    {
        return static::where('updated_at', '<', now()->subYears(2));
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function therapy(): BelongsTo
    {
        return $this->belongsTo(Therapy::class);
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /** Telefone no formato (19) 99999-9999. */
    public function formattedPhone(): string
    {
        $d = preg_replace('/\D/', '', (string) $this->phone);

        return match (strlen($d)) {
            11 => sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 5), substr($d, 7)),
            10 => sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 4), substr($d, 6)),
            default => (string) $this->phone,
        };
    }

    /** Assunto de interesse (curso, terapia ou profissional). */
    public function interest(): ?string
    {
        return $this->course?->title ?? $this->therapy?->title ?? $this->professional?->name;
    }
}
