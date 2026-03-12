<?php

namespace Mpietrucha\Filament\Essentials\QueryBuilder\Constraints\TrashedConstraint\Operators;

use Filament\QueryBuilder\Constraints\Operators\Operator;
use Illuminate\Database\Eloquent\Builder;

/**
 * @phpstan-type TrashedOperatorQuery \Illuminate\Database\Eloquent\Builder<\Illuminate\Database\Eloquent\Model>
 */
class TrashedOperator extends Operator
{
    public function getName(): string
    {
        return 'trashed';
    }

    public function getLabel(): string
    {
        return match (true) {
            $this->isInverse() => __('filament-essentials::query-builder.operators.trashed.label.inverse'),
            default => __('filament-essentials::query-builder.operators.trashed.label.direct')
        };
    }

    public function getSummary(): string
    {
        return match (true) {
            $this->isInverse() => __('filament-essentials::query-builder.operators.trashed.summary.inverse'),
            default => __('filament-essentials::query-builder.operators.trashed.summary.direct')
        };
    }

    /**
     * @param  TrashedOperatorQuery  $query
     * @return TrashedOperatorQuery
     */
    public function applyToBaseFilterQuery(Builder $query): Builder
    {
        return match (true) {
            $this->isInverse() => $query->onlyTrashed(), /** @phpstan-ignore method.notFound */
            default => $query->withTrashed(), /** @phpstan-ignore method.notFound */
        };
    }
}
