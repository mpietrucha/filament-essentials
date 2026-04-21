<?php

namespace Archilex\AdvancedTables\Filters;

use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class AdvancedFilter extends BaseFilter
{
    protected function getColumnType(Column $column): string
    {
    }

    protected function getTextColumnFilter(Column $column): BaseFilter
    {
    }
}

class TextFilter extends BaseFilter
{
    public static function make(string $name): static
    {
    }

    public function column(Column $column): static
    {
    }

    public function label(Htmlable|string $label): static
    {
    }

    public function apply(Builder $query, array $data = []): Builder
    {
    }

    public function getFormSchema(): array
    {
    }

    protected function getOperators(): array
    {
    }

    protected function formFilled(array $data): bool
    {
    }

    protected function getFilterIndicator(TextFilter $filter, array $data): array
    {
    }

    protected function getQueryColumn(Builder $query): string
    {
    }
}
