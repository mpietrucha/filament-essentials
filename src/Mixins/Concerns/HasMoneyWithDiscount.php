<?php

namespace Mpietrucha\Filament\Essentials\Mixins\Concerns;

use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;
use Mpietrucha\Filament\Essentials\Record;

/**
 * @internal
 */
trait HasMoneyWithDiscount
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

        Record::get(
            $currencyAttribute = Record::attribute($currencyAttribute, $relation)
        ) |> $component->money(...);

        return Record::pipe(static function (Record $record) use ($referencePriceAttribute, $relation, $currencyAttribute): ?HtmlString {
            $money = $record->money(
                Record::attribute($referencePriceAttribute, $relation),
                $record->get($currencyAttribute), /** @phpstan-ignore argument.type */
            );

            /** @var string $money */
            if ($money === '') { /** @phpstan-ignore varTag.nativeType */
                return null;
            }

            return new HtmlString(sprintf('<s>%s</s> ', $money));
        }) |> $component->prefix(...);
    }
}
