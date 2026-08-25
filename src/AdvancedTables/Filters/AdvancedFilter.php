<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filters;

use Archilex\AdvancedTables\Filters\AdvancedFilter as ArchilexAdvancedFilter;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Support\Arr;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;
use Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Contracts\InteractsWithTableColumnInterface;

if (class_exists(ArchilexAdvancedFilter::class)) {
    class AdvancedFilter extends ArchilexAdvancedFilter
    {
        public function getCollectedFilters(): array
        {
            $this->registerFilterColumns();

            return parent::getCollectedFilters();
        }

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

        protected function registerFilterColumns(): void
        {
            $filters = $this->getFilters() |> collect(...);

            $table = $this->getTable();

            $tableColumns = $table->getColumns();

            $columns = $filters->whereInstanceOf(InteractsWithTableColumnInterface::class)
                ->map
                ->getTableColumn()
                ->reject(static fn (Column $column) => Arr::exists($tableColumns, $column->getName()))
                ->each
                ->hidden()
                ->all();

            $table->pushColumns($columns);
        }
    }
} else {
    PackageException::missing('AdvancedFilter');
}
