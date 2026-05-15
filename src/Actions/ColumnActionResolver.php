<?php

namespace Mpietrucha\Filament\Essentials\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Tables\Columns\Column;

abstract class ColumnActionResolver
{
    public static function resolve(Column $column): null|Action|Closure
    {
        /** @phpstan-ignore property.notFound */
        $action = invade($column)->action;

        if (is_string($action)) {
            return null;
        }

        if (! $action instanceof PendingColumnAction) {
            return $action;
        }

        /** @var null|Action */
        return $action->getActionResolver() |> $column->evaluate(...);
    }
}
