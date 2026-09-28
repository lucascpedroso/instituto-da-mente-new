<?php

namespace App\Filament\Resources\Books\Tables;

use App\Models\Book;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                ImageColumn::make('cover')->label('Capa')->disk('public')->imageHeight(56),
                TextColumn::make('title')->label('Título')->searchable()->sortable()->weight('medium'),
                TextColumn::make('author')->label('Autor(a)')->searchable(),
                TextColumn::make('price')->label('Preço')->money('BRL')->sortable(),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($state) => Book::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'publicado' => 'success', 'esgotado' => 'warning', default => 'gray'
                    }),
                IconColumn::make('is_featured')->label('Destaque')->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')->options(Book::STATUSES),
            ])
            ->recordActions([
                Action::make('ver')->label('Ver no site')->icon('heroicon-o-arrow-top-right-on-square')->color('gray')
                    ->url(fn (Book $record) => route('books.show', $record), shouldOpenInNewTab: true)
                    ->visible(fn (Book $record) => $record->status !== 'rascunho'),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
