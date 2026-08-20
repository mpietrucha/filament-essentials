<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Locale\AdvancedTables\Filters;

use Archilex\AdvancedTables\Filters\SelectFilter as ArchilexSelectFilter;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;
use Mpietrucha\Filament\Essentials\Locale\Filters\CurrencyFilter as FilamentCurrencyFilter;

if (class_exists(ArchilexSelectFilter::class)) {
    class CurrencyFilter extends ArchilexSelectFilter
    {
        protected function setUp(): void
        {
            parent::setUp();

            FilamentCurrencyFilter::configureSelectFilter($this);
        }
    }
} else {
    PackageException::missing('CurrencyFilter');
}
