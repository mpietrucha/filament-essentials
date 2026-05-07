<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Closure;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Actions\CreateAction;
use Mpietrucha\Filament\Essentials\Actions\EditAction;
use Mpietrucha\Filament\Essentials\Actions\ViewAction;

/**
 * @phpstan-require-extends Resource
 */
trait ResourceMixin
{
    public static function getViewAction(?string $relation = null): ViewAction
    {
        $viewAction = ViewAction::make($relation);

        if (is_string($relation)) {
            static::applyActionRelationSchema($viewAction, $relation, static::infolist(...));
        }

        return static::configureViewAction($viewAction, $relation);
    }

    public static function configureViewAction(ViewAction $viewAction, ?string $relation = null): ViewAction
    {
        static::configureAction($viewAction, $relation);

        return $viewAction;
    }

    public static function getEditAction(?string $relation = null): EditAction
    {
        $editAction = EditAction::make($relation);

        if (is_string($relation)) {
            static::applyActionRelationSchema($editAction, $relation, static::form(...));
        }

        return static::configureEditAction($editAction, $relation);
    }

    public static function configureEditAction(EditAction $editAction, ?string $relation = null): EditAction
    {
        static::configureAction($editAction, $relation);

        return $editAction;
    }

    public static function getCreateAction(?string $relation = null): CreateAction
    {
        $createAction = CreateAction::make($relation);

        if (is_string($relation)) {
            static::applyActionRelationSchema($createAction, $relation, static::form(...));
        }

        return static::configureCreateAction($createAction, $relation);
    }

    public static function configureCreateAction(CreateAction $createAction, ?string $relation = null): CreateAction
    {
        static::configureAction($createAction, $relation);

        return $createAction;
    }

    public static function configureAction(Action $action, ?string $relation = null): Action
    {
        static::applyActionConfiguration($action, $relation);

        return $action;
    }

    public static function applyActionConfiguration(Action $action, ?string $relation = null): void
    {
        static::getNavigationIcon() |> $action->modalIcon(...);

        $action->modalIconColor(Color::Gray);

        static::applyDefaultActionConfiguration($action, $relation);
    }

    public static function applyDefaultActionConfiguration(Action $action, ?string $relation = null): void
    {
    }

    /**
     * @param  Closure(Schema): Schema  $handler
     */
    public static function applyActionRelationSchema(CreateAction|EditAction|ViewAction $action, string $relation, Closure $handler): void
    {
        $action->relation($relation);

        $action->schema(static function (Model $record, Schema $schema) use ($handler): Schema {
            return $schema->model($record) |> $handler;
        });
    }
}
