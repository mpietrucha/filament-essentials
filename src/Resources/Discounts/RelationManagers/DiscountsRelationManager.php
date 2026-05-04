<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Tables\Table;
use Mpietrucha\Filament\Essentials\RelationManagers\RelationManager;
use Mpietrucha\Filament\Essentials\Resources\Concerns\GuessesResource;

class DiscountsRelationManager extends RelationManager
{
    use GuessesResource;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions(static::headerActions())
            ->recordActions(static::recordActions())
            ->toolbarActions(static::toolbarActions());
    }

    /**
     * @return list<Action|ActionGroup>
     */
    protected static function headerActions(): array
    {
        return [];
    }

    /**
     * @return list<Action|ActionGroup>
     */
    protected static function recordActions(): array
    {
        /**
         * @var list<Action|ActionGroup>
         */
        return [
            static::getResource()::getEditAction(),
            static::getResource()::getFinishAction(),
        ];
    }

    /**
     * @return list<Action|ActionGroup>
     */
    protected static function toolbarActions(): array
    {
        return [];
    }
}
