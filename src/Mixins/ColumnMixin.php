<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Closure;
use Filament\Tables\Columns\Column;
use Illuminate\Support\Str;
use Mpietrucha\Filament\Essentials\Actions\TableColumnAction;
use Mpietrucha\Support\Backtrace;

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

    public function asNeighbor(?string $label = null): static
    {
        $this->label(static function () use ($label): string {
            if ($label === null) {
                return Str::none();
            }

            return Backtrace::get(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
                ->map
                ->getFunction()
                ->doesntContain('mapTableColumnToArray') ? Str::none() : $label;
        });

        return $this->width('1%');
    }
}
