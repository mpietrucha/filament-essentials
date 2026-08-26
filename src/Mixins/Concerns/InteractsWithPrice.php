<?php

namespace Mpietrucha\Filament\Essentials\Mixins\Concerns;

use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Laravel\Essentials\Money\PriceAttribute;

/**
 * @internal
 */
trait InteractsWithPrice
{
    public static function priceWithConversion(
        ?string $priceAttribute = null,
        ?string $convertedPriceAttribute = null,
        ?string $currencyAttribute = null,
        ?string $indicator = null,
        ?string $relation = null,
    ): static {
        $priceAttribute ??= PriceAttribute::getPrice($indicator);
        $convertedPriceAttribute ??= PriceAttribute::getConvertedPrice($indicator);

        $currencyAttribute ??= PriceAttribute::getCurrency();

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
        ?string $discountedPriceAttribute = null,
        ?string $referencePriceAttribute = null,
        ?string $convertedDiscountedPriceAttribute = null,
        ?string $currencyAttribute = null,
        ?string $indicator = null,
        ?string $relation = null,
    ): static {
        $discountedPriceAttribute ??= PriceAttribute::getDiscountedPrice($indicator);
        $referencePriceAttribute ??= PriceAttribute::getReferencePrice($indicator);
        $convertedDiscountedPriceAttribute ??= PriceAttribute::getConvertedDiscountedPrice($indicator);

        $currencyAttribute ??= PriceAttribute::getCurrency();

        $component = static::priceWithConversion(
            $discountedPriceAttribute,
            $convertedDiscountedPriceAttribute,
            $currencyAttribute,
            $indicator,
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
