<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Closure;
use Filament\Tables\Columns\Column;
use Mpietrucha\Filament\Essentials\Actions\PendingColumnAction;

/**
 * @phpstan-require-extends Column
 */
trait ColumnMixin
{
    public function resolveActionUsing(Closure $actionResolver): static
    {
        $pendingColumnAction = PendingColumnAction::make()->resolver($actionResolver);

        $this->action($pendingColumnAction);

        return $this;
    }
}
