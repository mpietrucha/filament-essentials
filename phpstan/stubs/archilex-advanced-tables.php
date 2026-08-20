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
    use Filament\Tables\Filters\SelectFilter as BaseSelectFilter;
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

    class SelectFilter extends BaseSelectFilter
    {
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

        public function apply(Builder $builder, array $data = []): Builder
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

        protected function getQueryColumn(Builder $builder): string
        {
        }

        protected function getColumn(): ?Column
        {
        }
    }
}
