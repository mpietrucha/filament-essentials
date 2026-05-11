<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Mpietrucha\Filament\Essentials\Plugins\DiscountsPlugin;
use Mpietrucha\Filament\Essentials\Resources\Discounts\DiscountResource;

class DiscountsRelationManager extends RelationManager
{
    #[\Override]
    protected static string $relationship = 'discounts';

    /**
     * @return class-string<DiscountResource>
     */
    #[\Override]
    public static function getRelatedResource(): string
    {
        /** @var class-string<DiscountResource> */
        return DiscountsPlugin::get()->getResource();
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions(static::headerActions())
            ->recordActions(static::recordActions())
            ->toolbarActions(static::toolbarActions());
    }

    /**
     * @return list<Action>
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
        return [
            static::getEditAction(),
            static::getRelatedResource()::getFinishAction(),
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
