<?php

namespace App\Filament\Resources\Professionals;

use App\Filament\Concerns\AdminOnly;
use App\Filament\Resources\Professionals\Pages\CreateProfessional;
use App\Filament\Resources\Professionals\Pages\EditProfessional;
use App\Filament\Resources\Professionals\Pages\ListProfessionals;
use App\Filament\Resources\Professionals\Schemas\ProfessionalForm;
use App\Filament\Resources\Professionals\Tables\ProfessionalsTable;
use App\Models\Professional;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProfessionalResource extends Resource
{
    use AdminOnly;

    protected static ?string $model = Professional::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    // O site usa o slug nas URLs; no painel usamos o ID, que não muda ao editar o slug.
    protected static ?string $recordRouteKeyName = 'id';

    protected static ?string $slug = 'profissionais';

    protected static ?string $modelLabel = 'profissional';

    protected static ?string $pluralModelLabel = 'Profissionais';

    protected static ?string $navigationLabel = 'Profissionais';

    protected static string|\UnitEnum|null $navigationGroup = 'Site';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProfessionalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProfessionalsTable::configure($table);
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
            'index' => ListProfessionals::route('/'),
            'create' => CreateProfessional::route('/create'),
            'edit' => EditProfessional::route('/{record}/edit'),
        ];
    }
}
