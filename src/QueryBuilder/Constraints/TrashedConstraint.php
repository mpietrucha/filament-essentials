<?php

namespace Mpietrucha\Filament\Essentials\QueryBuilder\Constraints;

use Filament\QueryBuilder\Constraints\Constraint;
use Filament\Support\Icons\Heroicon;
use Mpietrucha\Filament\Essentials\QueryBuilder\Constraints\TrashedConstraint\Operators\OnlyTrashedOperator;
use Mpietrucha\Filament\Essentials\QueryBuilder\Constraints\TrashedConstraint\Operators\WithTrashedOperator;

/**
 * @phpstan-type TrashedQuery \Illuminate\Database\Eloquent\Builder<\Illuminate\Database\Eloquent\Model>
 */
class TrashedConstraint extends Constraint
{
    protected function setUp(): void
    {
        parent::setUp();

        __('filament-essentials::query-builder.trashed_constraint.label') |> $this->label(...);

        $this->icon(Heroicon::Trash);

        $this->operators([
            WithTrashedOperator::make(),
            OnlyTrashedOperator::make(),
        ]);
    }
}
