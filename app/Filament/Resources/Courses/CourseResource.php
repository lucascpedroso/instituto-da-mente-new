<?php

namespace App\Filament\Resources\Courses;

use App\Filament\Concerns\AdminOnly;
use App\Filament\Resources\Courses\Pages\CreateCourse;
use App\Filament\Resources\Courses\Pages\EditCourse;
use App\Filament\Resources\Courses\Pages\ListCourses;
use App\Filament\Resources\Courses\Schemas\CourseForm;
use App\Filament\Resources\Courses\Tables\CoursesTable;
use App\Models\Course;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CourseResource extends Resource
{
    use AdminOnly;

    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    // O site usa o slug nas URLs; no painel usamos o ID, que não muda ao editar o slug.
    protected static ?string $recordRouteKeyName = 'id';

    protected static ?string $slug = 'cursos';

    protected static ?string $modelLabel = 'curso';

    protected static ?string $pluralModelLabel = 'Cursos e formação';

    protected static ?string $navigationLabel = 'Cursos e formação';

    protected static string|\UnitEnum|null $navigationGroup = 'Site';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return CourseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoursesTable::configure($table);
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
            'index' => ListCourses::route('/'),
            'create' => CreateCourse::route('/create'),
            'edit' => EditCourse::route('/{record}/edit'),
        ];
    }
}
