<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Money\AdvancedTables\Filters;

use Archilex\AdvancedTables\Filters\NumericFilter as ArchilexNumericFilter;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;
use Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Concerns\InteractsWithTableColumn;
use Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Contracts\InteractsWithTableColumnInterface;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Laravel\Essentials\Money\PriceAttribute;

if (class_exists(ArchilexNumericFilter::class)) {
    class PriceFilter extends ArchilexNumericFilter implements InteractsWithTableColumnInterface
    {
        use InteractsWithTableColumn;

        public static function make(?string $name = null, ?string $indicator = null, ?string $relation = null): static
        {
            if ($name === null) {
                $name = PriceAttribute::getDiscountedPrice($indicator);
            }

            return Record::buildRelationAttribute($name, $relation) |> parent::make(...);
        }
    }
} else {
    PackageException::missing('PriceFilter');
}
