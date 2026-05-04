<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Tables;

use Brick\Money\Money;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Table;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Enums\DiscountStatus;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;
use Mpietrucha\Laravel\Essentials\Locale;

class DiscountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(static::columns())
            ->filters(static::filters())
            ->recordActions(static::recordActions())
            ->toolbarActions(static::toolbarActions());
    }

    /**
     * @return list<Column>
     */
    protected static function columns(): array
    {
        return [
            static::getPriceColumn(),

            TextColumn::make('quota.usage')
                ->label(__('filament-essentials::discounts-plugin.table.usage'))
                ->placeholder('-'),

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

            TextColumn::make('status')
                ->label(__('filament-essentials::discounts-plugin.table.status'))
                ->badge()
                ->state(Record::pipe(static function (Record $record): DiscountStatus {
                    return $record->get('status') |> DiscountStatus::from(...);
                })),
        ];
    }

    /**
     * @return list<BaseFilter>
     */
    protected static function filters(): array
    {
        return [];
    }

    /**
     * @return list<Action|ActionGroup>
     */
    protected static function recordActions(): array
    {
        return [];
    }

    /**
     * @return list<Action|ActionGroup>
     */
    protected static function toolbarActions(): array
    {
        return [];
    }

    protected static function getPriceColumn(): TextColumn
    {
        return TextColumn::make('price')
            ->label(__('filament-essentials::discounts-plugin.table.price'))
            ->placeholder('-')
            ->state(static function (Discount $record): ?string {
                $price = $record->getPrice();

                if ($price instanceof Money) {
                    return Locale::get()->code() |> $price->formatToLocale(...);
                }

                $discountPercentage = $record->discount_percentage;

                if ($discountPercentage === null) {
                    return null;
                }

                return sprintf('%s %s%%', __('filament-essentials::discounts-plugin.table.discount'), $discountPercentage);
            });
    }
}
