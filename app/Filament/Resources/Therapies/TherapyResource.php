<?php

namespace App\Filament\Resources\Therapies;

use App\Filament\Concerns\AdminOnly;
use App\Filament\Resources\Therapies\Pages\CreateTherapy;
use App\Filament\Resources\Therapies\Pages\EditTherapy;
use App\Filament\Resources\Therapies\Pages\ListTherapies;
use App\Filament\Resources\Therapies\Schemas\TherapyForm;
use App\Filament\Resources\Therapies\Tables\TherapiesTable;
use App\Models\Therapy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TherapyResource extends Resource
{
    use AdminOnly;

    protected static ?string $model = Therapy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    // O site usa o slug nas URLs; no painel usamos o ID, que não muda ao editar o slug.
    protected static ?string $recordRouteKeyName = 'id';

    protected static ?string $slug = 'terapias';

    protected static ?string $modelLabel = 'terapia';

    protected static ?string $pluralModelLabel = 'Terapias';

    protected static ?string $navigationLabel = 'Terapias';

    protected static string|\UnitEnum|null $navigationGroup = 'Site';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return TherapyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TherapiesTable::configure($table);
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
            'index' => ListTherapies::route('/'),
            'create' => CreateTherapy::route('/create'),
            'edit' => EditTherapy::route('/{record}/edit'),
        ];
    }
}
