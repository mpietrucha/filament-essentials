<?php

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Concerns;

use Archilex\AdvancedTables\Filters\BooleanFilter;
use Archilex\AdvancedTables\Filters\DateFilter;
use Archilex\AdvancedTables\Filters\NumericFilter;
use Archilex\AdvancedTables\Filters\SelectFilter;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;
use Mpietrucha\Filament\Essentials\AdvancedTables\Filters\TextFilter;

if (class_exists(BooleanFilter::class) && class_exists(DateFilter::class) && class_exists(NumericFilter::class) && class_exists(SelectFilter::class) && class_exists(TextFilter::class)) {
    trait InteractsWithFilters
    {
        protected ?BooleanFilter $booleanFilter = null;

        protected ?DateFilter $dateFilter = null;

        protected ?NumericFilter $numericFilter = null;

        protected ?SelectFilter $selectFilter = null;

        protected ?TextFilter $textFilter = null;

        protected function getBooleanFilter(): BooleanFilter
        {
            if ($this->booleanFilter instanceof BooleanFilter) {
                return $this->booleanFilter;
            }

            return $this->booleanFilter = $this->getName() |> BooleanFilter::make(...);
        }

        protected function getDateFilter(): DateFilter
        {
            if ($this->dateFilter instanceof DateFilter) {
                return $this->dateFilter;
            }

            $dateFilter = $this->getName() |> DateFilter::make(...);

            return $this->dateFilter = $this->getFilterTableColumn($dateFilter) |> $dateFilter->column(...);
        }

        protected function getNumericFilter(): NumericFilter
        {
            if ($this->numericFilter instanceof NumericFilter) {
                return $this->numericFilter;
            }

            $numericFilter = $this->getName() |> NumericFilter::make(...);

            return $this->numericFilter = $this->getFilterTableColumn($numericFilter) |> $numericFilter->column(...);
        }

        protected function getSelectFilter(): SelectFilter
        {
            if ($this->selectFilter instanceof SelectFilter) {
                return $this->selectFilter;
            }

            $selectFilter = $this->getName() |> SelectFilter::make(...);

            return $this->selectFilter = $this->getFilterTableColumn($selectFilter) |> $selectFilter->column(...);
        }

        protected function getTextFilter(): TextFilter
        {
            if ($this->textFilter instanceof TextFilter) {
                return $this->textFilter;
            }

            $textFilter = $this->getName() |> TextFilter::make(...);

            return $this->textFilter = $this->getFilterTableColumn($textFilter) |> $textFilter->column(...);
        }

        protected function getFilterTableColumn(BaseFilter $filter): Column
        {
            $column = $this->getName() |> TextColumn::make(...);

            $this->getLabel() |> $column->label(...);

            return $column;
        }
    }
} else {
    PackageException::missing('InteractsWithFilters');
}
