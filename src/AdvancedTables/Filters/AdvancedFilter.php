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
            if ($this->isTextFilterColumnType($column)) {
                return TextFilter::fromColumn($column);
            }

            return parent::getTextColumnFilter($column);
        }

        protected function isTextFilterColumnType(Column $column): bool
        {
            if ($this->aggregatesRelationship($column)) {
                return false;
            }

            return $this->getColumnType($column) === 'text';
        }
    }
} else {
    PackageException::missing('AdvancedFilter');
}
