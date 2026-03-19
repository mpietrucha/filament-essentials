<?php

namespace Mpietrucha\Filament\Essentials\QueryBuilder\Constraints\TrashedConstraint\Operators;

use Filament\QueryBuilder\Constraints\Operators\Operator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @phpstan-type TrashedOperatorQuery Builder<Model>
 */
class TrashedOperator extends Operator
{
    #[\Override]
    public function getName(): string
    {
        return 'trashed';
    }

    #[\Override]
    public function getLabel(): string
    {
        return match (true) {
            $this->isInverse() => __('filament-tables::table.filters.trashed.with_trashed'),
            default => __('filament-tables::table.filters.trashed.only_trashed')
        };
    }

    #[\Override]
    public function getSummary(): string
    {
        return $this->getLabel();
    }

    /**
     * @param  TrashedOperatorQuery  $query
     * @return TrashedOperatorQuery
     */
    #[\Override]
    public function applyToBaseFilterQuery(Builder $query): Builder
    {
        /** @var TrashedOperatorQuery */
        return match (true) {
            $this->isInverse() => $query->withTrashed(), /** @phpstan-ignore method.notFound */
            default => $query->onlyTrashed(), /** @phpstan-ignore method.notFound */
        };
    }
}
