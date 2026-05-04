<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Tables;

use Brick\Money\Money;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;
use Mpietrucha\Laravel\Essentials\Locale;

class DiscountsTable
{
    public static function configure(Table $table): Table
    {
        return static::columns() |> $table->columns(...);
    }

    /**
     * @return list<Column>
     */
    protected static function columns(): array
    {
        return [
            static::getDiscountColumn(),

            static::getLimitColumn(),

            TextColumn::make('active_from')
                ->label(__('filament-essentials::discounts-plugin.table.active_from'))
                ->dateTime()
                ->placeholder('-'),

            TextColumn::make('active_to')
                ->label(__('filament-essentials::discounts-plugin.table.active_to'))
                ->dateTime()
                ->placeholder('-'),

            TextColumn::make('finished_at')
                ->label(__('filament-essentials::discounts-plugin.table.finished_at'))
                ->dateTime()
                ->placeholder('-'),
        ];
    }

    protected static function getDiscountColumn(): TextColumn
    {
        $label = __('filament-essentials::discounts-plugin.table.discount');

        return TextColumn::make('discount')
            ->label($label)
            ->placeholder('-')
            ->state(static function (Discount $record) use ($label): ?string {
                $price = $record->getPrice();

                if ($price instanceof Money) {
                    return Locale::get()->code() |> $price->formatToLocale(...);
                }

                $discountPercentage = $record->discount_percentage;

                if ($discountPercentage === null) {
                    return null;
                }

                return sprintf('%s %s%%', $label, $discountPercentage);
            });
    }

    protected static function getLimitColumn(): TextColumn
    {
        return TextColumn::make('quota.limit')
            ->label(__('filament-essentials::discounts-plugin.table.limit'))
            ->placeholder('-');
    }
}
