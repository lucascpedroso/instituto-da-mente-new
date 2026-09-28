<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Nome')->required()->maxLength(255),
                TextInput::make('email')->label('E-mail')->email()->required()->maxLength(255)->unique(ignoreRecord: true),
                Select::make('role')->label('Perfil')->options(User::ROLES)->default('admin')->required()->native(false)
                    ->helperText('Editor: gerencia apenas blog e livros.'),
                TextInput::make('password')->label('Senha')->password()->revealable()
                    ->rule('min:10')
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->helperText(fn (string $operation) => $operation === 'edit' ? 'Deixe em branco para manter a senha atual.' : 'Mínimo de 10 caracteres.'),
            ]);
    }
}
