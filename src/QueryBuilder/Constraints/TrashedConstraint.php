<?php

namespace Mpietrucha\Filament\Essentials\QueryBuilder\Constraints;

use Filament\QueryBuilder\Constraints\Constraint;
use Filament\Support\Icons\Heroicon;
use Mpietrucha\Filament\Essentials\QueryBuilder\Constraints\TrashedConstraint\Operators\TrashedOperator;

class TrashedConstraint extends Constraint
{
    protected function setUp(): void
    {
        parent::setUp();

        __('filament-tables::table.filters.trashed.label') |> $this->label(...);

        $this->icon(Heroicon::Trash);

        $this->operators([
            TrashedOperator::make(),
        ]);
    }
}
