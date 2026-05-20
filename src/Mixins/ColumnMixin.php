<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Closure;
use Filament\Tables\Columns\Column;
use Mpietrucha\Filament\Essentials\Actions\TableColumnAction;

/**
 * @phpstan-require-extends Column
 */
trait ColumnMixin
{
    public function resolveActionUsing(Closure $resolveActionUsing): static
    {
        TableColumnAction::make()->resolveActionUsing($resolveActionUsing) |> $this->action(...);

        return $this;
    }
}
