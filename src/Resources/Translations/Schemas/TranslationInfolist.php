<?php

namespace Mpietrucha\Filament\Essentials\Resources\Translations\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class TranslationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return static::components() |> $schema->components(...);
    }

    /**
     * @return list<Component>
     */
    protected static function components(): array
    {
        return [
            TextEntry::make('group'),
            TextEntry::make('key'),
            KeyValue::make('text'),
        ];
    }
}
