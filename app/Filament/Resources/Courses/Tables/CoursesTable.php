<?php

namespace App\Filament\Resources\Courses\Tables;

use App\Models\Course;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                TextColumn::make('title')->label('Curso')->searchable()->weight('medium'),
                TextColumn::make('format')->label('Formato')->formatStateUsing(fn ($state) => Course::FORMATS[$state] ?? $state),
                TextColumn::make('workload_hours')->label('Carga')->suffix('h'),
                IconColumn::make('is_flagship')->label('Carro-chefe')->boolean(),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($state) => Course::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => $state === 'publicado' ? 'success' : 'gray'),
                TextColumn::make('leads_count')->label('Inscrições')->counts('leads')->sortable(),
            ])
            ->recordActions([
                Action::make('ver')->label('Ver')->icon('heroicon-o-arrow-top-right-on-square')->color('gray')
                    ->url(fn (Course $record) => route('courses.show', $record), shouldOpenInNewTab: true)
                    ->visible(fn (Course $record) => $record->status === 'publicado'),
                EditAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
