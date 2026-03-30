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
            TextEntry::make('group')
                ->label(__('filament-essentials::translation.infolist.group'))
                ->columnSpanFull(),

            TextEntry::make('key')
                ->label(__('filament-essentials::translation.infolist.key'))
                ->columnSpanFull(),

            KeyValue::make('text')
                ->label(__('filament-essentials::translation.infolist.translations'))
                ->keyLabel(__('filament-essentials::translation.infolist.language'))
                ->valueLabel(__('filament-essentials::translation.infolist.text'))
                ->columnSpanFull(),

            TextEntry::make('created_at')
                ->label(__('filament-essentials::translation.infolist.created_at'))
                ->dateTime()
                ->placeholder('-')
                ->columnSpanFull(),

            TextEntry::make('updated_at')
                ->label(__('filament-essentials::translation.infolist.updated_at'))
                ->dateTime()
                ->placeholder('-')
                ->columnSpanFull(),
        ];
    }
}
