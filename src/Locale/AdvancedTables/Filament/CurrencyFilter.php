<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Locale\AdvancedTables\Filament;

use Archilex\AdvancedTables\Filament\SelectFilter as ArchilexSelectFilter;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;
use Mpietrucha\Filament\Essentials\Locale\Filters\Concerns\InteractsWithCurrencyFilter;

if (class_exists(ArchilexSelectFilter::class)) {
    class CurrencyFilter extends ArchilexSelectFilter
    {
        use InteractsWithCurrencyFilter;
    }
} else {
    PackageException::missing('CurrencyFilter');
}
