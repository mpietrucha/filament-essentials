<?php

namespace Archilex\AdvancedTables {
    trait AdvancedTables
    {
        public ?string $activeUserView = null;

        public ?string $activePresetView = null;

        public function resetTable(): void
        {
        }

        public function loadUserView(string $userView, ?array $filters = null, bool $resetTable = true, bool $isActive = true): void
        {
        }

        public function loadPresetView(string $presetView, ?array $filters = null, bool $resetTable = true, bool $isActive = true): void
        {
        }
    }
}

namespace Archilex\AdvancedTables\Filters {
    use Archilex\AdvancedTables\Filters\Concerns\HasColumn;
    use Filament\Tables\Columns\Column;
    use Filament\Tables\Filters\BaseFilter;
    use Illuminate\Database\Eloquent\Builder;

    class AdvancedFilter extends BaseFilter
    {
        protected function getFilterIndicators(AdvancedFilter $filter, array $data): array
        {
        }

        protected function getColumnType(Column $column): string
        {
        }

        protected function aggregatesRelationship(Column $column): bool
        {
        }

        protected function getTextColumnFilter(Column $column): BaseFilter
        {
        }
    }

    class BooleanFilter extends BaseFilter
    {
    }

    class DateFilter extends BaseFilter
    {
        use HasColumn;
    }

    class NumericFilter extends BaseFilter
    {
        use HasColumn;
    }

    class SelectFilter extends BaseFilter
    {
        use HasColumn;
    }

    class TextFilter extends BaseFilter
    {
        use HasColumn;

        protected function getOperators(): array
        {
        }

        protected function formFilled(array $data): bool
        {
        }

        protected function getQueryColumn(Builder $builder): string
        {
        }

        protected function getFilterIndicator(TextFilter $filter, array $data): array
        {
        }
    }
}

namespace Archilex\AdvancedTables\Filament {
    use Filament\Tables\Filters\Filter as FilamentFilter;
    use Filament\Tables\Filters\Indicator as FilamentIndicator;
    use Filament\Tables\Filters\SelectFilter as FilamentSelectFilter;

    class Filter extends FilamentFilter
    {
    }

    class SelectFilter extends FilamentSelectFilter
    {
    }

    class Indicator extends FilamentIndicator
    {
    }
}

namespace Archilex\AdvancedTables\Filters\Concerns {
    use Filament\Tables\Columns\Column;

    trait HasColumn
    {
        public function column(Column $column): static
        {
        }

        public function getColumn(): ?Column
        {
        }
    }
}
