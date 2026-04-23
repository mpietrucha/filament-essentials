<?php

namespace Mpietrucha\Filament\Essentials\Mixins\Concerns;

use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Mpietrucha\Filament\Essentials\Record;

/**
 * @internal
 */
trait BuildsMoneyWithDiscount
{
    public static function moneyWithDiscount(
        ?string $relation = null,
        ?string $label = null,
        string $discountedPriceAttribute = 'discounted_price',
        string $referencePriceAttribute = 'reference_price',
        string $currencyAttribute = 'currency',
        string $convertedDiscountedPriceAttribute = 'converted_discounted_price'
    ): static {
        $convertedDiscountedPrice = Record::attribute($convertedDiscountedPriceAttribute, $relation) |> Record::money(...);

        $component = Record::attribute($discountedPriceAttribute, $relation) |> static::make(...);

        if (is_string($label)) {
            $component->label($label);
        }

        if ($component instanceof TextEntry) { /** @phpstan-ignore instanceof.alwaysTrue, instanceof.alwaysFalse */
            $component->belowContent($convertedDiscountedPrice);
        }

        if ($component instanceof TextColumn) { /** @phpstan-ignore instanceof.alwaysTrue, instanceof.alwaysFalse */
            $component->description($convertedDiscountedPrice);
        }

        Record::attribute($currencyAttribute, $relation) |> Record::get(...) |> $component->money(...);

        Record::attribute($referencePriceAttribute, $relation) |> Record::money(...) |> $component->prefix(...);

        return $component;
    }
}
