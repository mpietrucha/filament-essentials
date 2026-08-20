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

        $this->multiple();

        $this->searchable();

        Currency::enum() |> $this->options(...);
    }
}
