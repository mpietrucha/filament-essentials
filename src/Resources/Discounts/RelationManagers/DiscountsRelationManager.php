<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Tables\Table;
use Mpietrucha\Filament\Essentials\RelationManagers\RelationManager;

class DiscountsRelationManager extends RelationManager
{
    public function table(Table $table): Table
    {
        return $table
            ->headerActions(static::headerActions())
            ->recordActions(static::recordActions())
            ->toolbarActions(static::toolbarActions());
    }

    public static function configureCreateAction(CreateAction $createAction): CreateAction
    {
        $createAction->hidden(static function (self $livewire): bool {
            if ($livewire->isModal()) {
                return true;
            }

            /** @phpstan-ignore property.notFound, method.nonObject */
            return $livewire->getOwnerRecord()->discounts->first->isActive() |> filled(...);
        });

        return $createAction;
    }

    /**
     * @return list<Action|ActionGroup>
     */
    protected static function headerActions(): array
    {
        return [
            static::getCreateAction(),
        ];
    }

    /**
     * @return list<Action|ActionGroup>
     */
    protected static function recordActions(): array
    {
        /** @var list<Action|ActionGroup> */
        return [
            static::getRelatedResource()::getEditAction(), /** @phpstan-ignore staticMethod.nonObject */
            static::getRelatedResource()::getFinishAction(), /** @phpstan-ignore staticMethod.nonObject */
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
