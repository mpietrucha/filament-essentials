<?php

namespace Mpietrucha\Filament\Essentials\Resources\Translations\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;
use Mpietrucha\Laravel\Essentials\Locale;

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
                ->required()
                ->alpha(),

            TextInput::make('key')
                ->label(__('filament-essentials::translation.form.key'))
                ->unique(modifyRuleUsing: static function (Unique $rule, Get $get): Unique {
                    /** @var string $group */
                    $group = $get('group');

                    return $rule->where('group', $group);
                })
                ->required()
                ->regex('/^[a-z_.]+$/'),

            Textarea::make('text')
                ->label(__('filament-essentials::translation.form.text'))
                ->required()
                ->columnSpanFull()
                ->translate(requiredLocales: Locale::enum()::default()),
        ];
    }
}
