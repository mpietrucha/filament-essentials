<?php

namespace Mpietrucha\Filament\Essentials\Mixins\Concerns;

use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Laravel\Essentials\Eloquent\Qualifiers\AttributeQualifier;
use Mpietrucha\Laravel\Essentials\Locale\Currency;
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
        ?string $relationship = null,
    ): static {
        $priceAttribute = AttributeQualifier::build(
            $priceAttribute ?? PriceAttribute::getPrice($indicator),
            $relationship,
        );

        $convertedPriceAttribute = AttributeQualifier::build(
            $convertedPriceAttribute ?? PriceAttribute::getConvertedPrice($indicator),
            $relationship,
        );

        $currencyAttribute = AttributeQualifier::build(
            $currencyAttribute ?? PriceAttribute::getCurrency(),
            $relationship,
        );

        $convertedPrice = Record::pipe(static function (Record $record) use ($convertedPriceAttribute, $currencyAttribute): ?string {
            $targetCurrency = Currency::get()->code();

            if ($record->get($currencyAttribute) === $targetCurrency) {
                return null;
            }

            return $record->money($convertedPriceAttribute, $targetCurrency);
        });

        $component = static::make($priceAttribute);

        if ($component instanceof TextEntry) { /** @phpstan-ignore instanceof.alwaysTrue, instanceof.alwaysFalse */
            $component->belowContent($convertedPrice);
        }

        if ($component instanceof TextColumn) { /** @phpstan-ignore instanceof.alwaysTrue, instanceof.alwaysFalse */
            $component->description($convertedPrice);
        }

        Record::get($currencyAttribute) |> $component->money(...);

        return $component;
    }

    public static function priceWithDiscount(
        ?string $discountedPriceAttribute = null,
        ?string $referencePriceAttribute = null,
        ?string $convertedDiscountedPriceAttribute = null,
        ?string $currencyAttribute = null,
        ?string $indicator = null,
        ?string $relationship = null,
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
            $relationship,
        );

        return Record::pipe(static function (Record $record) use ($referencePriceAttribute, $relationship, $currencyAttribute): ?HtmlString {
            $money = $record->money(
                AttributeQualifier::build($referencePriceAttribute, $relationship),
                AttributeQualifier::build($currencyAttribute, $relationship) |> $record->get(...),
            );

            if (blank($money)) {
                return null;
            }

            return new HtmlString(sprintf('<s>%s</s> ', $money));
        }) |> $component->prefix(...);
    }
}
