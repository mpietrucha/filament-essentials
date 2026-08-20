<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Locale\AdvancedTables\Filters;

use Archilex\AdvancedTables\Filters\SelectFilter as ArchilexSelectFilter;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;
use Mpietrucha\Laravel\Essentials\Locale\Currency;

if (class_exists(ArchilexSelectFilter::class)) {
    class CurrencyFilter extends ArchilexSelectFilter
    {
        protected function setUp(): void
        {
            parent::setUp();

            $this->searchable();

            Currency::enum() |> $this->options(...);
        }
    }
} else {
    PackageException::missing('CurrencyFilter');
}
