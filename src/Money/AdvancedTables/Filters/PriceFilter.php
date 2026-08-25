<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Money\AdvancedTables\Filters;

use Archilex\AdvancedTables\Filters\NumericFilter as ArchilexNumericFilter;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;

if (class_exists(ArchilexNumericFilter::class)) {
    class PriceFilter extends ArchilexNumericFilter
    {
    }
} else {
    PackageException::missing('PriceFilter');
}
