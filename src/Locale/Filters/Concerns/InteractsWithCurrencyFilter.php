<?php

namespace Mpietrucha\Filament\Essentials\Locale\Filters\Concerns;

use Filament\Tables\Filters\SelectFilter;
use Mpietrucha\Filament\Essentials\Record;
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
        $name ??= PriceAttribute::getCurrency();

        $selectFilter = Record::buildRelationshipAttribute($name, $relationship) |> parent::make(...);

        if ($relationship) {
            $selectFilter->queryThroughRelationship();
        }

        return $selectFilter;
    }
}
