<?php

namespace App\Filament\Resources\Leads\Tables;

use App\Models\Course;
use App\Models\Lead;
use App\Models\Therapy;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['course', 'therapy', 'professional']))
            ->columns([
                TextColumn::make('created_at')->label('Recebido')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('name')->label('Nome')->searchable()->weight('medium'),
                TextColumn::make('phone')->label('WhatsApp')->searchable()->formatStateUsing(fn ($state, Lead $record) => $record->formattedPhone())
                    ->url(fn (Lead $r) => 'https://wa.me/55'.$r->phone, shouldOpenInNewTab: true)->color('success'),
                TextColumn::make('type')->label('Tipo')->badge()
                    ->formatStateUsing(fn ($state) => Lead::TYPES[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'inscricao_curso' => 'warning', 'agendamento' => 'info', default => 'gray'
                    }),
                TextColumn::make('interesse')->label('Interesse')->state(fn (Lead $r) => $r->interest())->limit(35),
                TextColumn::make('utm_source')->label('Origem')->placeholder('Direto')->toggleable(),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($state) => Lead::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'novo' => 'danger', 'em_atendimento' => 'warning', default => 'success'
                    }),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')->options(Lead::STATUSES),
                SelectFilter::make('type')->label('Tipo')->options(Lead::TYPES),
                SelectFilter::make('course_id')->label('Curso')->options(fn () => Course::orderBy('title')->pluck('title', 'id')),
                SelectFilter::make('therapy_id')->label('Terapia')->options(fn () => Therapy::orderBy('title')->pluck('title', 'id')),
                Filter::make('ads')->label('Vindos de anúncios')->query(fn (Builder $query) => $query->where(fn (Builder $q) => $q->whereNotNull('gclid')->orWhereNotNull('fbclid')->orWhereNotNull('utm_source'))),
            ])
            ->headerActions([
                Action::make('exportar')->label('Exportar CSV')->icon('heroicon-o-arrow-down-tray')->color('gray')
                    ->action(fn () => self::csv(Lead::with(['course', 'therapy', 'professional'])->latest()->get())),
            ])
            ->recordActions([
                EditAction::make()->label('Abrir'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('em_atendimento')->label('Marcar como em atendimento')->icon('heroicon-o-chat-bubble-left-right')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'em_atendimento'])),
                    BulkAction::make('concluido')->label('Marcar como concluído')->icon('heroicon-o-check')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'concluido'])),
                    BulkAction::make('csv')->label('Exportar selecionados (CSV)')->icon('heroicon-o-arrow-down-tray')
                        ->action(fn (Collection $records) => self::csv($records)),
                    DeleteBulkAction::make()->label('Excluir (pedido LGPD)'),
                ]),
            ]);
    }

    private static function csv(Collection $leads): StreamedResponse
    {
        return response()->streamDownload(function () use ($leads) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM para o Excel abrir acentos corretamente
            fputcsv($out, ['Data', 'Tipo', 'Nome', 'Telefone', 'E-mail', 'Interesse', 'Modalidade', 'Mensagem', 'Status', 'utm_source', 'utm_medium', 'utm_campaign', 'Google Ads', 'Meta Ads', 'Página'], ';');
            foreach ($leads as $l) {
                fputcsv($out, [
                    $l->created_at->format('d/m/Y H:i'), $l->typeLabel(), $l->name, $l->formattedPhone(), $l->email, $l->interest(),
                    Lead::MODALITIES[$l->modality] ?? '', $l->message, Lead::STATUSES[$l->status] ?? $l->status,
                    $l->utm_source, $l->utm_medium, $l->utm_campaign, $l->gclid ? 'sim' : '', $l->fbclid ? 'sim' : '', $l->source_url,
                ], ';');
            }
            fclose($out);
        }, 'contatos-instituto-da-mente-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
