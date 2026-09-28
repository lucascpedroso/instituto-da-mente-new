<?php

namespace App\Filament\Resources\Professionals\Schemas;

use App\Filament\Support\Fields;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProfessionalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Profissional')
                    ->columnSpan(2)
                    ->schema([
                        ...Fields::titleAndSlug('name', 'Nome', 'profissionais'),
                        Grid::make(2)->schema([
                            TextInput::make('profession')->label('Profissão / cargo')->required()->maxLength(255)->placeholder('Ex.: Psicanalista clínico'),
                            TextInput::make('registration')->label('Registro profissional')->maxLength(255)->placeholder('Ex.: CRP 06/000000')
                                ->helperText('Obrigatório para psicólogos(as).'),
                        ]),
                        Textarea::make('short_bio')->label('Apresentação curta')->maxLength(500)->rows(3)->helperText('Aparece nos cards e no Google.'),
                        TagsInput::make('specialties')->label('Especialidades')->placeholder('Digite e pressione Enter'),
                        Fields::richText('bio', 'Biografia'),
                        Fields::richText('education', 'Formação e especializações'),
                    ]),
                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Exibição')->schema([
                            Toggle::make('is_active')->label('Visível no site')->default(true),
                            Toggle::make('is_featured')->label('Destaque (fundador)'),
                            TextInput::make('sort')->label('Ordem de exibição')->numeric()->default(0),
                        ]),
                        Section::make('Foto')->schema([
                            Fields::image('photo', 'profissionais', 'Foto profissional')->imageAspectRatio('4:5'),
                        ]),
                        Section::make('Agendamento')->schema([
                            TextInput::make('whatsapp')->label('WhatsApp próprio (opcional)')->tel()->maxLength(20)
                                ->helperText('Com DDI e DDD, só números. Ex.: 5519999999999. Se vazio, usa o WhatsApp do Instituto.'),
                        ]),
                    ]),
            ]);
    }
}
