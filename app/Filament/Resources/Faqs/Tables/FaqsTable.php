<?php

namespace App\Filament\Resources\Faqs\Tables;

use App\Models\Faq;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                TextColumn::make('question')->label('Pergunta')->searchable()->wrap()->weight('medium'),
                TextColumn::make('group')->label('Grupo')->badge()->formatStateUsing(fn ($state) => Faq::GROUPS[$state] ?? $state),
                ToggleColumn::make('show_on_home')->label('Na home'),
                ToggleColumn::make('is_active')->label('Visível'),
            ])
            ->filters([SelectFilter::make('group')->label('Grupo')->options(Faq::GROUPS)])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
