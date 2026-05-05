<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;

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
            Fieldset::make(__('filament-essentials::discounts-plugin.form.details'))
                ->columnSpanFull()
                ->schema([
                    TextInput::make('price')
                        ->label(__('filament-essentials::discounts-plugin.form.price')),

                    TextInput::make('discount_percentage')
                        ->label(__('filament-essentials::discounts-plugin.form.discount_percentage')),
                ]),

            Fieldset::make(__('filament-essentials::discounts-plugin.form.activity'))
                ->columnSpanFull()
                ->schema([
                    DatePicker::make('active_from')
                        ->label(__('filament-essentials::discounts-plugin.form.active_from')),

                    DatePicker::make('active_to')
                        ->label(__('filament-essentials::discounts-plugin.form.active_to')),
                ]),

            Fieldset::make(__('filament-essentials::discounts-plugin.form.quota.label'))
                ->columnSpanFull()
                ->schema([
                    ToggleButtons::make('quota_type')
                        ->label(__('filament-essentials::discounts-plugin.form.quota.type'))
                        ->options([
                            'none' => __('filament-essentials::discounts-plugin.form.quota.none'),
                            'existing' => __('filament-essentials::discounts-plugin.form.quota.existing'),
                            'new' => __('filament-essentials::discounts-plugin.form.quota.new'),
                        ])
                        ->inline()
                        ->afterStateHydrated(static function (ToggleButtons $component, ?Discount $record): void {
                            match (true) {
                                $record?->quota === null => 'none',
                                default => 'existing',
                            } |> $component->state(...);
                        })
                        ->live()
                        ->dehydrated(false)
                        ->columnSpanFull(),

                    Select::make('quota_id')
                        ->searchable()
                        ->preload()
                        ->label(__('filament-essentials::discounts-plugin.form.quota.label'))
                        ->relationship('quota', 'name')
                        ->visible(static fn (Get $get): bool => $get('quota_type') === 'existing')
                        ->columnSpanFull(),

                    Group::make()
                        ->columns(2)
                        ->columnSpanFull()
                        ->relationship('quota')
                        ->visible(static fn (Get $get): bool => $get('quota_type') === 'new')
                        ->schema([
                            TextInput::make('name')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.name')),

                            TextInput::make('limit')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.limit')),

                            DatePicker::make('active_from')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.active_from')),

                            DatePicker::make('active_to')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.active_to')),
                        ]),

                    Group::make()
                        ->relationship('quota')
                        ->columnSpanFull()
                        ->schema([
                            Textarea::make('notes')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.notes'))
                                ->columnSpanFull(),
                        ]),
                ]),
        ];
    }
}
