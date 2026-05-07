<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Mpietrucha\Filament\Essentials\Actions\CreateAction;
use Mpietrucha\Filament\Essentials\Actions\EditAction;
use Mpietrucha\Filament\Essentials\Actions\ViewAction;
use Mpietrucha\Support\Exception\RuntimeException;

/**
 * @phpstan-require-extends RelationManager
 *
 * @phpstan-type FilamentResource class-string<Resource>
 */
trait RelationManagerMixin
{
    /**
     * @return FilamentResource
     */
    public static function getActionsResource(): string
    {
        /** @var FilamentResource */
        return static::getRelatedResource() ?? RuntimeException::throw('Related resource cannot be empty');
    }

    public static function getViewAction(?string $relation = null): ViewAction
    {
        /** @var ViewAction */
        return static::getActionsResource()::getViewAction($relation);
    }

    public static function getEditAction(?string $relation = null): EditAction
    {
        /** @var EditAction */
        return static::getActionsResource()::getEditAction($relation);
    }

    public static function getCreateAction(?string $relation = null): CreateAction
    {
        /** @var CreateAction */
        return static::getActionsResource()::getCreateAction($relation);
    }
}
