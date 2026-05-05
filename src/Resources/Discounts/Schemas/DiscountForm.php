<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class DiscountForm
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
                ->columns(2)
                ->schema([
                    TextInput::make('price')
                        ->label(__('filament-essentials::discounts-plugin.form.price'))
                        ->numeric(),

                    TextInput::make('discount_percentage')
                        ->label(__('filament-essentials::discounts-plugin.form.discount_percentage'))
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(99)
                        ->suffix('%'),

                    DatePicker::make('active_from')
                        ->label(__('filament-essentials::discounts-plugin.form.active_from')),

                    DatePicker::make('active_to')
                        ->label(__('filament-essentials::discounts-plugin.form.active_to')),

                    ToggleButtons::make('quota_type')
                        ->label(__('filament-essentials::discounts-plugin.form.quota_type'))
                        ->live()
                        ->dehydrated(false)
                        ->columnSpanFull()
                        ->default('none')
                        ->options([
                            'none' => __('filament-essentials::discounts-plugin.form.quota.none'),
                            'existing' => __('filament-essentials::discounts-plugin.form.quota.existing'),
                            'new' => __('filament-essentials::discounts-plugin.form.quota.new'),
                        ]),

                    Select::make('quota_id')
                        ->label(__('filament-essentials::discounts-plugin.form.quota.existing'))
                        ->relationship('quota', 'name')
                        ->visible(static function (Get $get): bool {
                            return $get('quota_type') === 'existing';
                        })
                        ->columnSpanFull(),

                    Fieldset::make(__('filament-essentials::discounts-plugin.form.quota.new'))
                        ->relationship('quota')
                        ->columns(2)
                        ->visible(static function (Get $get): bool {
                            return $get('quota_type') === 'new';
                        })
                        ->schema([
                            TextInput::make('name')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.name')),

                            TextInput::make('limit')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.limit'))
                                ->numeric()
                                ->minValue(1),

                            DatePicker::make('active_from')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.active_from')),

                            DatePicker::make('active_to')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.active_to')),
                        ])
                        ->columnSpanFull(),
                ]),
        ];
    }
}
