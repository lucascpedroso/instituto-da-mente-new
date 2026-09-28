<?php

namespace App\Filament\Resources\Books\Schemas;

use App\Filament\Support\Fields;
use App\Models\Book;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Livro')
                    ->columnSpan(2)
                    ->schema([
                        ...Fields::titleAndSlug(prefix: 'livros'),
                        TextInput::make('author')->label('Autor(a)')->required()->maxLength(255)->default('Ricardo Mello'),
                        Textarea::make('synopsis')->label('Sinopse curta')->helperText('Aparece nas listagens e no Google (até 600 caracteres).')->required()->maxLength(600)->rows(3),
                        Fields::richText('description', 'Descrição completa'),
                    ]),
                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Publicação')->schema([
                            Select::make('status')->label('Status')->options(Book::STATUSES)->default('publicado')->required()->native(false),
                            Toggle::make('is_featured')->label('Destaque na página inicial'),
                            TextInput::make('sort')->label('Ordem de exibição')->numeric()->default(0)->helperText('Menor número aparece primeiro.'),
                        ]),
                        Section::make('Capa')->schema([
                            Fields::image('cover', 'livros', 'Capa do livro')->imageAspectRatio('2:3'),
                        ]),
                        Section::make('Venda')->schema([
                            TextInput::make('purchase_url')->label('Link de compra')->url()->maxLength(255)->helperText('Loja externa. Se vazio, o botão abre o WhatsApp.'),
                            TextInput::make('price')->label('Preço')->numeric()->prefix('R$')->minValue(0),
                            TextInput::make('category')->label('Categoria')->maxLength(255),
                        ]),
                    ]),
            ]);
    }
}
