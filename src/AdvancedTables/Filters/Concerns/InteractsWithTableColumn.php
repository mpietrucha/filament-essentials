<?php

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Concerns;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Contracts\InteractsWithTableColumnInterface;

/**
 * @phpstan-require-implements InteractsWithTableColumnInterface
 */
trait InteractsWithTableColumn
{
    public function getTableColumn(): Column
    {
        return $this->getDefaultTableColumn();
    }

    protected function getDefaultTableColumn(): Column
    {
        return $this->getName() |> TextColumn::make(...);
    }
}
