<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Filament\Support\Fields;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(Fields::titleAndSlug('name', 'Nome', 'blog/categoria'));
    }
}
