<?php

namespace App\Filament\Resources\Leads\Schemas;

use App\Models\Lead;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        $show = fn (string $name, string $label, \Closure $value) => TextEntry::make($name)->label($label)->state(fn (?Lead $record) => $record ? $value($record) : null)->placeholder('—');

        return $schema
            ->columns(3)
            ->components([
                Section::make('Dados do contato')
                    ->columnSpan(2)
                    ->schema([
                        Grid::make(2)->schema([
                            $show('p_name', 'Nome', fn (Lead $r) => $r->name),
                            $show('p_type', 'Tipo', fn (Lead $r) => $r->typeLabel()),
                            $show('p_phone', 'Telefone / WhatsApp', fn (Lead $r) => $r->formattedPhone())
                                ->url(fn (?Lead $record) => $record ? 'https://wa.me/55'.$record->phone : null, shouldOpenInNewTab: true)
                                ->color('success')->icon('heroicon-o-chat-bubble-left-ellipsis'),
                            $show('p_email', 'E-mail', fn (Lead $r) => $r->email)
                                ->url(fn (?Lead $record) => $record?->email ? 'mailto:'.$record->email : null),
                            $show('p_interest', 'Interesse', fn (Lead $r) => $r->interest()),
                            $show('p_modality', 'Modalidade', fn (Lead $r) => Lead::MODALITIES[$r->modality] ?? null),
                        ]),
                        $show('p_message', 'Mensagem', fn (Lead $r) => $r->message),
                    ]),
                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Atendimento')->schema([
                            Select::make('status')->label('Status')->options(Lead::STATUSES)->required()->native(false),
                            Textarea::make('notes')->label('Anotações internas')->rows(5),
                        ]),
                        Section::make('Origem')->collapsible()->schema([
                            $show('p_date', 'Recebido em', fn (Lead $r) => $r->created_at->format('d/m/Y H:i')),
                            $show('p_source', 'Página', fn (Lead $r) => $r->source_url),
                            $show('p_utm', 'Campanha (UTM)', fn (Lead $r) => collect([$r->utm_source, $r->utm_medium, $r->utm_campaign, $r->utm_content, $r->utm_term])->filter()->implode(' / ')),
                            $show('p_ads', 'Anúncios', fn (Lead $r) => collect(['Google Ads' => $r->gclid, 'Meta Ads' => $r->fbclid])->filter()->keys()->implode(', ')),
                            $show('p_consent', 'Consentimento LGPD', fn (Lead $r) => $r->consent_at?->format('d/m/Y H:i')),
                        ]),
                    ]),
            ]);
    }
}
