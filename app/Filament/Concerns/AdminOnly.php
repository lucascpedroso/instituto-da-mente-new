<?php

namespace App\Filament\Concerns;

/**
 * Restringe o recurso ao perfil Administrador (o perfil Editor gerencia apenas blog e livros).
 */
trait AdminOnly
{
    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }
}
