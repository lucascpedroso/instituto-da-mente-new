<?php

namespace App\Filament\Resources\Therapies\Schemas;

use App\Filament\Support\Fields;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TherapyForm
{
    public const ICONS = [
        'brain' => 'Cérebro', 'heart' => 'Coração', 'users' => 'Pessoas / família', 'sprout' => 'Broto (crianças)',
        'sun' => 'Sol', 'rainbow' => 'Arco-íris', 'cloud' => 'Nuvem', 'network' => 'Rede / sistema',
        'spiral' => 'Espiral', 'flame' => 'Chama', 'book' => 'Livro', 'clipboard' => 'Prancheta',
        'shield' => 'Escudo', 'award' => 'Selo',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Atendimento')
                    ->columnSpan(2)
                    ->schema([
                        ...Fields::titleAndSlug(prefix: 'terapias'),
                        Textarea::make('summary')->label('Resumo')->required()->maxLength(500)->rows(2),
                        Textarea::make('for_whom')->label('Para quem é indicado')->rows(3),
                        Fields::richText('body', 'Descrição completa'),
                    ]),
                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Exibição')->schema([
                            Toggle::make('is_active')->label('Visível no site')->default(true),
                            Select::make('icon')->label('Ícone')->options(self::ICONS)->native(false),
                            TextInput::make('sort')->label('Ordem de exibição')->numeric()->default(0),
                        ]),
                        Section::make('Imagem (opcional)')->schema([
                            Fields::image('image', 'terapias', 'Imagem')->imageAspectRatio('16:9'),
                        ]),
                        Section::make('SEO (Google)')->collapsible()->collapsed()->schema([
                            Textarea::make('meta_description')->label('Descrição para o Google')->maxLength(300)->rows(3),
                        ]),
                    ]),
            ]);
    }
}
