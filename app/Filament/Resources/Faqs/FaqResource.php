<?php

namespace App\Filament\Resources\Faqs;

use App\Filament\Concerns\AdminOnly;
use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Filament\Resources\Faqs\Schemas\FaqForm;
use App\Filament\Resources\Faqs\Tables\FaqsTable;
use App\Models\Faq;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    use AdminOnly;

    protected static ?string $model = Faq::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static ?string $slug = 'duvidas';

    protected static ?string $modelLabel = 'pergunta';

    protected static ?string $pluralModelLabel = 'Dúvidas frequentes';

    protected static ?string $navigationLabel = 'Dúvidas frequentes';

    protected static string|\UnitEnum|null $navigationGroup = 'Site';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'question';

    public static function form(Schema $schema): Schema
    {
        return FaqForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FaqsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFaqs::route('/'),
            'create' => CreateFaq::route('/create'),
            'edit' => EditFaq::route('/{record}/edit'),
        ];
    }
}
