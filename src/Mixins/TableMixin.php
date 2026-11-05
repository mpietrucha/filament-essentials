<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

/**
 * @phpstan-require-extends Table
 */
trait TableMixin
{
    public function registerIconActions(): static
    {
        $columns = $this->getColumns();

        Collection::make($columns)
            ->whereInstanceOf(TextColumn::class)
            ->map
            ->getIconAction()
            ->filter()
            ->each(fn (Action $action) => $action->table($this) |> $this->cacheAction(...));

        return $this;
    }
}
