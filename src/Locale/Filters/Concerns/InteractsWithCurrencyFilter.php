<?php

namespace Mpietrucha\Filament\Essentials\Locale\Filters\Concerns;

use Filament\Tables\Filters\SelectFilter;
use Mpietrucha\Laravel\Essentials\Eloquent\Qualifiers\AttributeQualifier;
use Mpietrucha\Laravel\Essentials\Locale\Currency;
use Mpietrucha\Laravel\Essentials\Money\PriceAttribute;

/**
 * @phpstan-require-extends SelectFilter
 */
trait InteractsWithCurrencyFilter
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->multiple();

        $this->searchable();

        Currency::enum() |> $this->options(...);
    }

    public static function make(?string $name = null, ?string $relationship = null): static
    {
        $selectFilter = AttributeQualifier::build($name ?? PriceAttribute::getCurrency(), $relationship) |> parent::make(...);

        return $relationship ? $selectFilter->queryThroughRelationship() : $selectFilter;
    }
}
