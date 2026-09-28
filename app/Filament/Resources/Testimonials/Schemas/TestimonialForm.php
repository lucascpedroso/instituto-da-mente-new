<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Models\Testimonial;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(2)->schema([
                    TextInput::make('name')->label('Nome ou iniciais')->required()->maxLength(80),
                    Select::make('kind')->label('Tipo')->options(Testimonial::KINDS)->default('paciente')->required()->native(false),
                ]),
                Textarea::make('content')->label('Depoimento')->required()->rows(5)->maxLength(1500),
                Grid::make(3)->schema([
                    Select::make('status')->label('Status')->options(Testimonial::STATUSES)->default('aprovado')->required()->native(false),
                    Toggle::make('is_featured')->label('Destaque na página inicial')->inline(false),
                    TextInput::make('sort')->label('Ordem')->numeric()->default(0),
                ]),
            ]);
    }
}
