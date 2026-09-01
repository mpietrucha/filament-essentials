<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filters;

use Archilex\AdvancedTables\Filters\AdvancedFilter as ArchilexAdvancedFilter;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;
use Mpietrucha\Filament\Essentials\AdvancedTables\Filament\Indicator;

if (class_exists(ArchilexAdvancedFilter::class)) {
    /**
     * @phpstan-type ListCollection Collection<int, non-falsy-string>
     * @phpstan-type FormData array<string, mixed>
     * @phpstan-type EloquentBuilder Builder<Model>
     * @phpstan-type OperatorArray array<string, string>
     * @phpstan-type IndicatorArray array<string>
     */
    class AdvancedFilter extends ArchilexAdvancedFilter
    {
        /**
         * @param  FormData  $data
         * @return FormData
         */
        #[\Override]
        protected function getFilterIndicators(ArchilexAdvancedFilter $archilexAdvancedFilter, array $data): array
        {
            /** @var FormData $indicators */
            $indicators = parent::getFilterIndicators($archilexAdvancedFilter, $data);

            /** @var FormData */
            return Arr::mapWithKeys($indicators, static function (mixed $indicator, string $key): array {
                if (! $indicator instanceof Indicator) {
                    return [$key => $indicator];
                }

                return [$indicator->getTransformedKey($key) => $indicator];
            });
        }

        #[\Override]
        protected function getTextColumnFilter(Column $column): BaseFilter
        {
            if (! $this->aggregatesRelationship($column) && $this->getColumnType($column) === 'text') {
                return TextFilter::fromColumn($column);
            }

            return parent::getTextColumnFilter($column);
        }
    }
} else {
    PackageException::missing('AdvancedFilter');
}
