<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use BackedEnum;
use Filament\Actions\Exports\ExportColumn;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Laravel\Essentials\Eloquent\Qualifiers\AttributeQualifier;
use Mpietrucha\Laravel\Essentials\Money\PriceAttribute;
use Mpietrucha\Support\Str;

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

    public function enum(?string $glue = null): static
    {
        $this->formatStateUsing(static function (mixed $state) use ($glue): mixed {
            $values = Collection::wrap($state)->map(static function (mixed $value): mixed {
                if ($value instanceof HasLabel) {
                    return $value->getLabel();
                }

                return $value instanceof BackedEnum ? $value->value : null;
            })->filter();

            if ($values->isEmpty()) {
                return $state;
            }

            if ($values->containsOneItem()) {
                return $values->first();
            }

            if ($glue) {
                return $values->join($glue);
            }

            return sprintf('%s ', Str::comma()) |> $values->join(...);
        });

        return $this;
    }
}
