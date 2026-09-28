<?php

namespace App\Filament\Resources\Tags\Schemas;

use App\Filament\Support\Fields;
use Filament\Schemas\Schema;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(Fields::titleAndSlug('name', 'Nome', null));
    }
}
