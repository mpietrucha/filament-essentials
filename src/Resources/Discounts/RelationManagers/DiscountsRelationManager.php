<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\Relation;
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

    #[\Override]
    public static function configureCreateAction(CreateAction $createAction): CreateAction
    {
        $createAction->hidden(static function (self $livewire): bool {
            if ($livewire->isModal()) {
                return true;
            }

            /** @phpstan-ignore property.notFound, method.nonObject, property.nonObject */
            return $livewire->getOwnerRecord()->discounts->first->isActive() |> filled(...);
        });

        $createAction->using(static function (array $data, self $livewire): Model {
            $model = $livewire->getOwnerRecord();

            /** @phpstan-ignore method.notFound */
            $discounts = $model->discounts();

            /** @var Relation<Model, Model, mixed> $relationship */
            $relationship = match (true) {
                $discounts instanceof HasManyThrough => $discounts->getParent()
                    ->newQuery()
                    ->where($discounts->getFirstKeyName(), $model->getKey())
                    ->sole()
                    ->discounts(), /** @phpstan-ignore method.notFound */
                default => $discounts,
            };

            /** @var array<string, mixed> $data */
            return $relationship->create($data);
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
