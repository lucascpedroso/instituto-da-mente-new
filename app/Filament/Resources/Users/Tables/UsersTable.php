<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nome')->searchable()->weight('medium'),
                TextColumn::make('email')->label('E-mail')->searchable(),
                TextColumn::make('role')->label('Perfil')->badge()->formatStateUsing(fn ($state) => User::ROLES[$state] ?? $state),
                TextColumn::make('created_at')->label('Criado em')->date('d/m/Y'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
