<?php

namespace Archilex\AdvancedTables {
    trait AdvancedTables
    {
        public ?string $activePresetView = null;

        public function resetTable(): void
        {
        }

        /**
         * @param  null|array<mixed>  $filters
         */
        public function loadPresetView(string $presetView, ?array $filters = null, bool $resetTable = true, bool $isActive = true): void
        {
        }
    }
}

namespace Archilex\AdvancedTables\Filters {
    use Filament\Tables\Columns\Column;
    use Filament\Tables\Filters\BaseFilter;
    use Illuminate\Database\Eloquent\Builder;

    class AdvancedFilter extends BaseFilter
    {
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

    class TextFilter extends BaseFilter
    {
        public function column(Column $column): static
        {
        }

        protected function getColumn(): ?Column
        {
        }

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
    use Filament\Tables\Filters\SelectFilter as FilamentSelectFilter;

    class SelectFilter extends FilamentSelectFilter
    {
    }
}
