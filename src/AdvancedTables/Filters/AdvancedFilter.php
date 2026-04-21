<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filters;

use Archilex\AdvancedTables\Filters\AdvancedFilter as ArchilexAdvancedFilter;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;

if (class_exists(ArchilexAdvancedFilter::class)) {
    class AdvancedFilter extends ArchilexAdvancedFilter
    {
        protected function getTextColumnFilter(Column $column): BaseFilter
        {
            $type = $this->getColumnType($column);

            if ($type !== 'text') {
                return parent::getTextColumnFilter($column);
            }

            $name = $column->getName();
            $label = $column->getLabel();

            return TextFilter::make($name)->column($column)->label($label);
        }
    }
} else {
    PackageException::missing('AdvancedFilter');
}
