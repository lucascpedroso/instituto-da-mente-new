<?php

namespace App\Filament\Support;

use App\Services\ImageOptimizer;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Campos reutilizados nos formulários do painel.
 */
class Fields
{
    /** Título + slug (URL), preenchido automaticamente enquanto o slug não for editado. */
    public static function titleAndSlug(string $field = 'title', string $label = 'Título', ?string $prefix = ''): array
    {
        return [
            TextInput::make($field)
                ->label($label)
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                    if (blank($get('slug')) || $get('slug') === Str::slug((string) $old)) {
                        $set('slug', Str::slug((string) $state));
                    }
                }),
            TextInput::make('slug')
                ->label('Endereço (URL)')
                ->prefix($prefix === null ? null : url($prefix).'/')
                ->helperText('Gerado a partir do título. Use apenas letras minúsculas, números e hífens.')
                ->required()
                ->alphaDash()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
        ];
    }

    /** Upload de imagem otimizada para WebP (com miniatura) na hospedagem. */
    public static function image(string $field, string $directory, string $label = 'Imagem'): FileUpload
    {
        return FileUpload::make($field)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->maxSize(8192)
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->imageEditor()
            ->helperText('JPG, PNG ou WebP, até 8 MB. A imagem é otimizada automaticamente.')
            ->saveUploadedFileUsing(fn (TemporaryUploadedFile $file) => ImageOptimizer::store($file, $directory));
    }

    public static function richText(string $field, string $label = 'Conteúdo'): RichEditor
    {
        return RichEditor::make($field)
            ->label($label)
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory('editor')
            ->fileAttachmentsVisibility('public')
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'link'],
                ['h2', 'h3', 'blockquote'],
                ['bulletList', 'orderedList'],
                ['attachFiles'],
                ['undo', 'redo'],
            ])
            ->columnSpanFull();
    }
}
