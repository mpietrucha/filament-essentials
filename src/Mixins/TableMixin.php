<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * @phpstan-require-extends Table
 */
trait TableMixin
{
    public function registerIconActions(): static
    {
        $columns = $this->getColumns();

        collect($columns)
            ->whereInstanceOf(TextColumn::class)
            ->map
            ->getIconAction()
            ->filter()
            ->all() |> $this->pushRecordActions(...);

        return $this;
    }
}
