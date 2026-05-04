<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\RelationManagers;

use Filament\Tables\Table;
use Mpietrucha\Filament\Essentials\RelationManagers\RelationManager;

class DiscountsRelationManager extends RelationManager
{
    public function table(Table $table): Table
    {
        return $table;
    }
}
