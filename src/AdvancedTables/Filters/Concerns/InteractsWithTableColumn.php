<?php

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Concerns;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;

trait InteractsWithTableColumn
{
    public function getTableColumn(): Column
    {
        return $this->getDefaultTableColumn();
    }

    protected function getDefaultTableColumn(): Column
    {
        $defaultColumn = $this->getName() |> TextColumn::make(...);

        $this->getLabel() |> $defaultColumn->label(...);

        return $defaultColumn;
    }
}
