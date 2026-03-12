<?php

namespace Mpietrucha\Filament\Essentials\QueryBuilder\Constraints\TrashedConstraint\Operators;

use Filament\QueryBuilder\Constraints\Operators\Operator;
use Illuminate\Database\Eloquent\Builder;

/**
 * @phpstan-import-type TrashedQuery from \Mpietrucha\Filament\Essentials\QueryBuilder\Constraints\TrashedConstraint
 */
class WithTrashedOperator extends Operator
{
    public function getName(): string
    {
        return 'with_trashed';
    }

    public function getLabel(): string
    {
        return __('filament-essentials::query-builder.trashed_constraint.operators.with_trashed_operator.label');
    }

    public function getSummary(): string
    {
        return __('filament-essentials::query-builder.trashed_constraint.operators.with_trashed_operator.summary');
    }

    /**
     * @param  TrashedQuery  $query
     * @return TrashedQuery
     */
    public function applyToBaseFilterQuery(Builder $query): Builder
    {
        /** @phpstan-ignore method.notFound */
        return $query->withTrashed();
    }
}
