<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Leads\LeadResource;
use App\Models\Lead;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestLeads extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Últimos contatos recebidos';

    public function table(Table $table): Table
    {
        return $table
            ->query(Lead::query()->with(['course', 'therapy', 'professional'])->latest()->limit(8))
            ->paginated(false)
            ->columns([
                TextColumn::make('created_at')->label('Recebido')->since(),
                TextColumn::make('name')->label('Nome')->weight('medium'),
                TextColumn::make('type')->label('Tipo')->badge()->formatStateUsing(fn ($state) => Lead::TYPES[$state] ?? $state),
                TextColumn::make('interesse')->label('Interesse')->state(fn (Lead $r) => $r->interest())->limit(30),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($state) => Lead::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'novo' => 'danger', 'em_atendimento' => 'warning', default => 'success'
                    }),
            ])
            ->recordActions([
                Action::make('whatsapp')->label('WhatsApp')->icon('heroicon-o-chat-bubble-left-ellipsis')->color('success')
                    ->url(fn (Lead $r) => 'https://wa.me/55'.$r->phone, shouldOpenInNewTab: true),
                Action::make('abrir')->label('Abrir')->url(fn (Lead $r) => LeadResource::getUrl('edit', ['record' => $r])),
            ]);
    }

    public static function canView(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }
}
