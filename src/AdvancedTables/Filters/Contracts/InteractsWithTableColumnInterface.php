<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Contracts;

use Filament\Tables\Columns\Column;

interface InteractsWithTableColumnInterface
{
    public function getTableColumn(): Column;
}
