<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Filament\Support\Fields;
use App\Models\Course;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Curso')
                    ->columnSpan(2)
                    ->schema([
                        ...Fields::titleAndSlug(prefix: 'formacao'),
                        Textarea::make('summary')->label('Resumo')->helperText('Aparece no topo da página e nas listagens.')->required()->maxLength(500)->rows(3),
                        Fields::richText('body', 'Descrição completa'),
                        Grid::make(2)->schema([
                            Textarea::make('prerequisites')->label('Pré-requisitos')->rows(3),
                            Textarea::make('certification')->label('Certificação')->rows(3),
                        ]),
                    ]),
                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Publicação')->schema([
                            Select::make('status')->label('Status')->options(Course::STATUSES)->default('publicado')->required()->native(false),
                            Toggle::make('is_flagship')->label('Curso carro-chefe (destaque)'),
                            TextInput::make('sort')->label('Ordem de exibição')->numeric()->default(0),
                        ]),
                        Section::make('Detalhes')->schema([
                            Select::make('format')->label('Formato')->options(Course::FORMATS)->default('hibrido')->required()->native(false),
                            TextInput::make('workload_hours')->label('Carga horária')->numeric()->minValue(1)->suffix('horas'),
                            TextInput::make('duration_text')->label('Duração')->placeholder('Ex.: 18 meses'),
                            TextInput::make('price_text')->label('Investimento (texto)')->placeholder('Ex.: Consulte condições'),
                            TextInput::make('checkout_url')->label('Link de pagamento (opcional)')->url()
                                ->helperText('Se preenchido, o botão principal leva direto ao pagamento (Hotmart, Mercado Pago etc.).'),
                        ]),
                        Section::make('Imagem')->schema([
                            Fields::image('cover', 'cursos', 'Imagem de capa')->imageAspectRatio('16:9'),
                        ]),
                        Section::make('SEO (Google)')->collapsible()->collapsed()->schema([
                            Textarea::make('meta_description')->label('Descrição para o Google')->maxLength(300)->rows(3),
                        ]),
                    ]),
            ]);
    }
}
