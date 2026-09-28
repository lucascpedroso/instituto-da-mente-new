<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover')->label('Capa')->disk('public')->imageHeight(44),
                TextColumn::make('title')->label('Título')->searchable()->sortable()->weight('medium')->limit(60),
                TextColumn::make('category.name')->label('Categoria')->sortable(),
                TextColumn::make('author.name')->label('Autor(a)')->toggleable(),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($state) => Post::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'publicado' => 'success', 'agendado' => 'info', default => 'gray'
                    }),
                TextColumn::make('published_at')->label('Publicação')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')->options(Post::STATUSES),
                SelectFilter::make('category_id')->label('Categoria')->relationship('category', 'name'),
            ])
            ->recordActions([
                Action::make('ver')->label('Ver')->icon('heroicon-o-arrow-top-right-on-square')->color('gray')
                    ->url(fn (Post $record) => route('blog.show', $record), shouldOpenInNewTab: true)
                    ->visible(fn (Post $record) => Post::published()->whereKey($record->id)->exists()),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('despublicar')->label('Despublicar (voltar para rascunho)')->icon('heroicon-o-eye-slash')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'rascunho'])),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
