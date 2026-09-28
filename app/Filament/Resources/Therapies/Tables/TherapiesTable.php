<?php

namespace App\Filament\Resources\Therapies\Tables;

use App\Models\Therapy;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TherapiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                TextColumn::make('title')->label('Atendimento')->searchable()->weight('medium'),
                TextColumn::make('summary')->label('Resumo')->limit(70)->toggleable(),
                ToggleColumn::make('is_active')->label('Visível'),
                TextColumn::make('leads_count')->label('Agendamentos')->counts('leads')->sortable(),
            ])
            ->recordActions([
                Action::make('ver')->label('Ver')->icon('heroicon-o-arrow-top-right-on-square')->color('gray')
                    ->url(fn (Therapy $record) => route('therapies.show', $record), shouldOpenInNewTab: true),
                EditAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
