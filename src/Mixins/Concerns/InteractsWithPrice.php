<?php

namespace Mpietrucha\Filament\Essentials\Mixins\Concerns;

use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;
use Mpietrucha\Filament\Essentials\Record;

/**
 * @internal
 */
trait InteractsWithPrice
{
    public static function priceWithConversion(
        string $priceAttribute = 'price',
        string $convertedPriceAttribute = 'converted_price',
        string $currencyAttribute = 'currency',
        ?string $relation = null,
    ): static {
        $component = Record::buildRelationAttribute($priceAttribute, $relation) |> static::make(...);

        $convertedPrice = Record::buildRelationAttribute($convertedPriceAttribute, $relation) |> Record::money(...);

        if ($component instanceof TextEntry) { /** @phpstan-ignore instanceof.alwaysTrue, instanceof.alwaysFalse */
            $component->belowContent($convertedPrice);
        }

        if ($component instanceof TextColumn) { /** @phpstan-ignore instanceof.alwaysTrue, instanceof.alwaysFalse */
            $component->description($convertedPrice);
        }

        Record::get(Record::buildRelationAttribute($currencyAttribute, $relation)) |> $component->money(...);

        return $component;
    }

    public static function priceWithDiscount(
        string $discountedPriceAttribute = 'discounted_price',
        string $referencePriceAttribute = 'reference_price',
        string $convertedDiscountedPriceAttribute = 'converted_discounted_price',
        string $currencyAttribute = 'currency',
        ?string $relation = null,
    ): static {
        $component = static::priceWithConversion(
            $discountedPriceAttribute,
            $convertedDiscountedPriceAttribute,
            $currencyAttribute,
            $relation,
        );

        return Record::pipe(static function (Record $record) use ($referencePriceAttribute, $relation, $currencyAttribute): ?HtmlString {
            $money = $record->money(
                Record::buildRelationAttribute($referencePriceAttribute, $relation),
                Record::buildRelationAttribute($currencyAttribute, $relation) |> $record->get(...),
            );

            if ($money === '') {
                return null;
            }

            return new HtmlString(sprintf('<s>%s</s> ', $money));
        }) |> $component->prefix(...);
    }
}
