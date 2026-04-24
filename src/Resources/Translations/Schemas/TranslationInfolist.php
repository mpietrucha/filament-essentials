<?php

namespace Mpietrucha\Filament\Essentials\Resources\Translations\Schemas;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
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
            Group::make()
                ->columnSpanFull()
                ->inlineLabel()
                ->schema([
                    TextEntry::make('group')
                        ->label(__('filament-essentials::translations-plugin.infolist.group')),

                    TextEntry::make('key')
                        ->label(__('filament-essentials::translations-plugin.infolist.key')),
                ]),

            KeyValueEntry::make('text')
                ->hiddenLabel()
                ->keyLabel(__('filament-essentials::translations-plugin.infolist.language'))
                ->valueLabel(__('filament-essentials::translations-plugin.infolist.text'))
                ->columnSpanFull(),

            Group::make()
                ->columnSpanFull()
                ->inlineLabel()
                ->schema([
                    TextEntry::make('created_at')
                        ->label(__('filament-essentials::translations-plugin.infolist.created_at'))
                        ->dateTime()
                        ->placeholder('-'),

                    TextEntry::make('updated_at')
                        ->label(__('filament-essentials::translations-plugin.infolist.updated_at'))
                        ->dateTime()
                        ->placeholder('-'),
                ]),
        ];
    }
}
