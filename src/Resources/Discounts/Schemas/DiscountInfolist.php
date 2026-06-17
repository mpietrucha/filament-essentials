<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Schemas;

use Brick\Money\Money;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Mpietrucha\Filament\Essentials\Plugins\DiscountsPlugin;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;
use Mpietrucha\Laravel\Essentials\Locale;

class DiscountInfolist
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
            Section::make(__('filament-essentials::discounts-plugin.infolist.discount'))
                ->columnSpanFull()
                ->inlineLabel()
                ->schema([
                    static::getPriceComponent(),

                    TextEntry::make('discount_percentage')
                        ->label(__('filament-essentials::discounts-plugin.infolist.discount_percentage'))
                        ->formatStateUsing(static function (?int $state): ?string {
                            if ($state === null) {
                                return null;
                            }

                            return sprintf('-%s%%', $state);
                        })
                        ->placeholder('-'),

                    TextEntry::make('status')
                        ->label(__('filament-essentials::discounts-plugin.infolist.status'))
                        ->badge()
                        /** @phpstan-ignore staticMethod.notFound */
                        ->state(DiscountsPlugin::get()->getResource()::getRecordStatus(...)),

                    TextEntry::make('active_from')
                        ->label(__('filament-essentials::discounts-plugin.infolist.active_from'))
                        ->date()
                        ->placeholder('-'),

                    TextEntry::make('active_to')
                        ->label(__('filament-essentials::discounts-plugin.infolist.active_to'))
                        ->date()
                        ->placeholder('-'),

                    TextEntry::make('finished_at')
                        ->label(__('filament-essentials::discounts-plugin.infolist.finished_at'))
                        ->date()
                        ->placeholder('-'),
                ]),

            Section::make(__('filament-essentials::discounts-plugin.infolist.quota.label'))
                ->columnSpanFull()
                ->inlineLabel()
                ->visible(static fn (Discount $discount): bool => $discount->quota !== null)
                ->schema([
                    TextEntry::make('quota.name')
                        ->label(__('filament-essentials::discounts-plugin.infolist.quota.name'))
                        ->placeholder('-'),

                    TextEntry::make('quota.usage')
                        ->label(__('filament-essentials::discounts-plugin.infolist.quota.usage'))
                        ->placeholder('-'),

                    TextEntry::make('quota.active_from')
                        ->label(__('filament-essentials::discounts-plugin.infolist.quota.active_from'))
                        ->date()
                        ->placeholder('-'),

                    TextEntry::make('quota.active_to')
                        ->label(__('filament-essentials::discounts-plugin.infolist.quota.active_to'))
                        ->date()
                        ->placeholder('-'),

                    TextEntry::make('quota.notes')
                        ->label(__('filament-essentials::discounts-plugin.infolist.quota.notes'))
                        ->formatStateUsing(static function (?string $state): ?string {
                            if ($state === null) {
                                return null;
                            }

                            return e($state) |> nl2br(...);
                        })
                        ->html()
                        ->placeholder('-'),
                ]),
        ];
    }

    protected static function getPriceComponent(): TextEntry
    {
        return TextEntry::make('price')
            ->label(__('filament-essentials::discounts-plugin.infolist.price'))
            ->state(static function (Discount $discount): ?string {
                $price = $discount->getPrice();

                if (! $price instanceof Money) {
                    return null;
                }

                return Locale::get()->code() |> $price->formatToLocale(...);
            })
            ->placeholder('-');
    }
}
