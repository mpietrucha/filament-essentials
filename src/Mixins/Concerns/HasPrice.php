<?php

namespace Mpietrucha\Filament\Essentials\Mixins\Concerns;

use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;
use Mpietrucha\Filament\Essentials\Record;

/**
 * @internal
 */
trait HasPrice
{
    public static function priceWithConversion(
        ?string $relation = null,
        ?string $label = null,
        string $priceAttribute = 'price',
        string $convertedPriceAttribute = 'converted_price',
        string $currencyAttribute = 'currency',
    ): static {
        $component = Record::attribute($priceAttribute, $relation) |> static::make(...);

        if (is_string($label)) {
            $component->label($label);
        }

        $convertedPrice = Record::attribute($convertedPriceAttribute, $relation) |> Record::money(...);

        if ($component instanceof TextEntry) { /** @phpstan-ignore instanceof.alwaysTrue, instanceof.alwaysFalse */
            $component->belowContent($convertedPrice);
        }

        if ($component instanceof TextColumn) { /** @phpstan-ignore instanceof.alwaysTrue, instanceof.alwaysFalse */
            $component->description($convertedPrice);
        }

        Record::get(
            Record::attribute($currencyAttribute, $relation)
        ) |> $component->money(...);

        return $component;
    }

    public static function priceWithDiscount(
        ?string $relation = null,
        ?string $label = null,
        string $discountedPriceAttribute = 'discounted_price',
        string $referencePriceAttribute = 'reference_price',
        string $convertedDiscountedPriceAttribute = 'converted_discounted_price',
        string $currencyAttribute = 'currency',
    ): static {
        $component = static::priceWithConversion(
            $relation,
            $label,
            $discountedPriceAttribute,
            $convertedDiscountedPriceAttribute,
            $currencyAttribute,
        );

        return Record::pipe(static function (Record $record) use ($referencePriceAttribute, $relation, $currencyAttribute): ?HtmlString {
            $money = $record->money(
                Record::attribute($referencePriceAttribute, $relation),
                Record::attribute($currencyAttribute, $relation) |> $record->get(...),
            );

            if ($money === '') {
                return null;
            }

            return new HtmlString(sprintf('<s>%s</s> ', $money));
        }) |> $component->prefix(...);
    }
}
