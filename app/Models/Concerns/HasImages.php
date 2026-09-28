<?php

namespace App\Models\Concerns;

use App\Services\ImageOptimizer;
use Illuminate\Support\Facades\Storage;

/**
 * URLs públicas das imagens enviadas pelo painel (original otimizado + miniatura)
 * e limpeza automática dos arquivos substituídos ou excluídos.
 */
trait HasImages
{
    protected static function bootHasImages(): void
    {
        static::updated(function ($model) {
            $field = $model->imageAttribute();

            if ($model->wasChanged($field) && ($old = $model->getOriginal($field)) && $old !== $model->{$field}) {
                ImageOptimizer::delete($old);
            }
        });

        static::deleted(fn ($model) => ImageOptimizer::delete($model->{$model->imageAttribute()}));
    }

    public function imageUrl(?string $attribute = null): ?string
    {
        $path = $this->{$attribute ?? $this->imageAttribute()};

        return $path ? Storage::disk('public')->url($path) : null;
    }

    public function thumbUrl(?string $attribute = null): ?string
    {
        $path = $this->{$attribute ?? $this->imageAttribute()};

        if (! $path) {
            return null;
        }

        $thumb = ImageOptimizer::thumbPath($path);

        return Storage::disk('public')->exists($thumb)
            ? Storage::disk('public')->url($thumb)
            : Storage::disk('public')->url($path);
    }

    public function imageAttribute(): string
    {
        return property_exists($this, 'imageField') ? $this->imageField : 'cover';
    }
}
