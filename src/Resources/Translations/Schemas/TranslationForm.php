<?php

namespace Mpietrucha\Filament\Essentials\Resources\Translations\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class TranslationForm
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
            TextInput::make('group')
                ->label(__('filament-essentials::translation.form.group'))
                ->required(),

            TextInput::make('key')
                ->label(__('filament-essentials::translation.form.key'))
                ->unique()
                ->required(),

            Textarea::make('text')
                ->label(__('filament-essentials::translation.form.text'))
                ->required()
                ->columnSpanFull()
                ->translate(),
        ];
    }
}
