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
                        ->label(__('filament-essentials::resource.translation.infolist.group')),

                    TextEntry::make('key')
                        ->label(__('filament-essentials::resource.translation.infolist.key')),
                ]),

            KeyValueEntry::make('text')
                ->hiddenLabel()
                ->keyLabel(__('filament-essentials::resource.translation.infolist.language'))
                ->valueLabel(__('filament-essentials::resource.translation.infolist.text'))
                ->columnSpanFull(),

            Group::make()
                ->columnSpanFull()
                ->inlineLabel()
                ->schema([
                    TextEntry::make('created_at')
                        ->label(__('filament-essentials::resource.translation.infolist.created_at'))
                        ->dateTime()
                        ->placeholder('-'),

                    TextEntry::make('updated_at')
                        ->label(__('filament-essentials::resource.translation.infolist.updated_at'))
                        ->dateTime()
                        ->placeholder('-'),
                ]),
        ];
    }
}
