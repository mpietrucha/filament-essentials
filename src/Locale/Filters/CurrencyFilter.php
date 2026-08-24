<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Locale\Filters;

use Filament\Tables\Filters\SelectFilter;
use Mpietrucha\Laravel\Essentials\Locale\Currency;

class CurrencyFilter extends SelectFilter
{
    protected function setUp(): void
    {
        parent::setUp();

        static::configureSelectFilter($this);
    }

    public static function configureSelectFilter(SelectFilter $selectFilter): void
    {
        $selectFilter->multiple();

        $selectFilter->searchable();

        Currency::enum() |> $selectFilter->options(...);
    }
}
