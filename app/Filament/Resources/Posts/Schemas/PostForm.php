<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Support\Fields;
use App\Models\Post;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Post')
                    ->columnSpan(2)
                    ->schema([
                        ...Fields::titleAndSlug(prefix: 'blog'),
                        Textarea::make('excerpt')->label('Resumo')->helperText('Aparece abaixo do título e nas listagens.')->maxLength(500)->rows(2),
                        Fields::richText('body', 'Texto do post')->required(),
                    ]),
                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Publicação')->schema([
                            Select::make('status')->label('Status')->options(Post::STATUSES)->default('rascunho')->required()->native(false)->live(),
                            DateTimePicker::make('published_at')->label('Data de publicação')->seconds(false)
                                ->required(fn (Get $get) => $get('status') === 'agendado')
                                ->helperText(fn (Get $get) => $get('status') === 'agendado'
                                    ? 'O post vai ao ar automaticamente nesta data.'
                                    : 'Em branco = data de agora ao publicar.'),
                            Select::make('author_id')->label('Autor(a)')->relationship('author', 'name')->searchable()->preload(),
                            Select::make('category_id')->label('Categoria')->relationship('category', 'name')->searchable()->preload()
                                ->createOptionForm([TextInput::make('name')->label('Nome')->required()]),
                            Select::make('tags')->label('Tags')->relationship('tags', 'name')->multiple()->searchable()->preload()
                                ->createOptionForm([TextInput::make('name')->label('Nome')->required()]),
                        ]),
                        Section::make('Imagem de capa')->schema([
                            Fields::image('cover', 'blog', 'Capa')->imageAspectRatio('16:9'),
                        ]),
                        Section::make('SEO (Google)')->collapsible()->schema([
                            TextInput::make('meta_title')->label('Título para o Google')->maxLength(70)->helperText('Opcional. Até 60–70 caracteres.'),
                            Textarea::make('meta_description')->label('Descrição para o Google')->maxLength(300)->rows(3)->helperText('Opcional. Ideal entre 120 e 160 caracteres.'),
                        ]),
                    ]),
            ]);
    }
}
