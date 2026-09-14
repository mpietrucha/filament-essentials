<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\Exports\ExportColumn;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Laravel\Essentials\Eloquent\Qualifiers\AttributeQualifier;
use Mpietrucha\Laravel\Essentials\Money\PriceAttribute;

/**
 * @phpstan-require-extends ExportColumn
 */
trait ExportColumnMixin
{
    public static function price(
        ?string $priceAttribute = null,
        ?string $currencyAttribute = null,
        ?string $indicator = null,
        ?string $relationship = null,
    ): static {
        $priceAttribute ??= PriceAttribute::getPrice($indicator);

        $currencyAttribute ??= PriceAttribute::getCurrency();

        $component = AttributeQualifier::build($priceAttribute, $relationship) |> static::make(...);

        return Record::pipe(static function (Record $record) use ($priceAttribute, $relationship, $currencyAttribute): string {
            return $record->money(
                AttributeQualifier::build($priceAttribute, $relationship),
                AttributeQualifier::build($currencyAttribute, $relationship) |> $record->get(...),
            );
        }) |> $component->state(...);
    }

    public function boolean(?string $trueLabel = null, ?string $falseLabel = null): static
    {
        $trueLabel ??= __('filament-forms::components.radio.boolean.true');
        $falseLabel ??= __('filament-forms::components.radio.boolean.false');

        $this->formatStateUsing(static fn (mixed $state): string => match ((bool) $state) {
            true => $trueLabel,
            false => $falseLabel,
        });

        return $this;
    }
}
