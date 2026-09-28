<?php

namespace App\Filament\Resources\Faqs\Schemas;

use App\Models\Faq;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('question')->label('Pergunta')->required()->maxLength(255),
                Textarea::make('answer')->label('Resposta')->required()->rows(5),
                Grid::make(4)->schema([
                    Select::make('group')->label('Grupo')->options(Faq::GROUPS)->default('geral')->required()->native(false),
                    TextInput::make('sort')->label('Ordem')->numeric()->default(0),
                    Toggle::make('show_on_home')->label('Mostrar na página inicial')->inline(false),
                    Toggle::make('is_active')->label('Visível')->default(true)->inline(false),
                ]),
            ]);
    }
}
