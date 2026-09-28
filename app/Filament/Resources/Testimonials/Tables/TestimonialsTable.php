<?php

namespace App\Filament\Resources\Testimonials\Tables;

use App\Models\Testimonial;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Enviado')->date('d/m/Y')->sortable(),
                TextColumn::make('name')->label('Nome')->searchable()->weight('medium'),
                TextColumn::make('kind')->label('Tipo')->formatStateUsing(fn ($state) => Testimonial::KINDS[$state] ?? $state),
                TextColumn::make('content')->label('Depoimento')->limit(80)->wrap(),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($state) => Testimonial::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'aprovado' => 'success', 'rejeitado' => 'danger', default => 'warning'
                    }),
                ToggleColumn::make('is_featured')->label('Destaque'),
            ])
            ->filters([SelectFilter::make('status')->label('Status')->options(Testimonial::STATUSES)->default('pendente')])
            ->recordActions([
                Action::make('aprovar')->label('Aprovar')->icon('heroicon-o-check')->color('success')
                    ->visible(fn (Testimonial $r) => $r->status !== 'aprovado')
                    ->action(fn (Testimonial $r) => $r->update(['status' => 'aprovado'])),
                Action::make('rejeitar')->label('Rejeitar')->icon('heroicon-o-x-mark')->color('danger')
                    ->visible(fn (Testimonial $r) => $r->status !== 'rejeitado')
                    ->requiresConfirmation()
                    ->action(fn (Testimonial $r) => $r->update(['status' => 'rejeitado'])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('aprovar')->label('Aprovar selecionados')->icon('heroicon-o-check')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'aprovado'])),
                    BulkAction::make('rejeitar')->label('Rejeitar selecionados')->icon('heroicon-o-x-mark')->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'rejeitado'])),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
