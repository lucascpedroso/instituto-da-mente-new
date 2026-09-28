<?php

namespace App\Filament\Resources\Professionals\Tables;

use App\Models\Professional;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProfessionalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                ImageColumn::make('photo')->label('Foto')->disk('public')->circular(),
                TextColumn::make('name')->label('Nome')->searchable()->weight('medium'),
                TextColumn::make('profession')->label('Profissão')->limit(40),
                IconColumn::make('is_featured')->label('Destaque')->boolean(),
                ToggleColumn::make('is_active')->label('Visível'),
            ])
            ->recordActions([
                Action::make('ver')->label('Ver')->icon('heroicon-o-arrow-top-right-on-square')->color('gray')
                    ->url(fn (Professional $record) => route('professionals.show', $record), shouldOpenInNewTab: true),
                EditAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
