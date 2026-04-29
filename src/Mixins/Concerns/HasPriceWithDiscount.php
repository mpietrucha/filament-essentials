<?php

namespace Mpietrucha\Filament\Essentials\Mixins\Concerns;

use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;
use Mpietrucha\Filament\Essentials\Record;

/**
 * @internal
 */
trait HasPriceWithDiscount
{
    public static function priceWithDiscount(
        ?string $relation = null,
        ?string $label = null,
        string $currencyAttribute = 'currency',
        string $discountedPriceAttribute = 'discounted_price',
        string $referencePriceAttribute = 'reference_price',
        string $convertedDiscountedPriceAttribute = 'converted_discounted_price',
    ): static {
        $component = Record::attribute($discountedPriceAttribute, $relation) |> static::make(...);

        if (is_string($label)) {
            $component->label($label);
        }

        $convertedDiscountedPrice = Record::attribute($convertedDiscountedPriceAttribute, $relation) |> Record::money(...);

        if ($component instanceof TextEntry) { /** @phpstan-ignore instanceof.alwaysTrue, instanceof.alwaysFalse */
            $component->belowContent($convertedDiscountedPrice);
        }

        if ($component instanceof TextColumn) { /** @phpstan-ignore instanceof.alwaysTrue, instanceof.alwaysFalse */
            $component->description($convertedDiscountedPrice);
        }

        Record::get(
            $currencyAttribute = Record::attribute($currencyAttribute, $relation)
        ) |> $component->money(...);

        return Record::pipe(static function (Record $record) use ($referencePriceAttribute, $relation, $currencyAttribute): ?HtmlString {
            $money = $record->money(
                Record::attribute($referencePriceAttribute, $relation),
                $record->get($currencyAttribute),
            );

            if ($money === '') {
                return null;
            }

            return new HtmlString(sprintf('<s>%s</s> ', $money));
        }) |> $component->prefix(...);
    }
}
