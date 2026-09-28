<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Gera o slug automaticamente a partir do título/nome quando vazio,
 * garantindo unicidade na tabela.
 */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            if (blank($model->slug)) {
                $model->slug = $model->uniqueSlug(Str::slug($model->{$model->slugSource()}));
            }
        });
    }

    protected function slugSource(): string
    {
        return property_exists($this, 'slugFrom') ? $this->slugFrom : 'title';
    }

    protected function uniqueSlug(string $base): string
    {
        $slug = $base ?: Str::random(8);
        $i = 2;

        while (static::where('slug', $slug)->whereKeyNot($this->getKey())->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
