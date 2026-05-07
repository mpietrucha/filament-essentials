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

        static::configureViewAction($viewAction, $relation);

        return $viewAction;
    }

    public static function configureViewAction(Action $action, ?string $relation = null): Action
    {
        static::configureAction($action, $relation);

        return $action;
    }

    public static function getEditAction(?string $relation = null): EditAction
    {
        $editAction = EditAction::make($relation);

        if (is_string($relation)) {
            static::applyActionRelationSchema($editAction, $relation, static::form(...));
        }

        static::configureEditAction($editAction, $relation);

        return $editAction;
    }

    public static function configureEditAction(Action $action, ?string $relation = null): Action
    {
        static::configureAction($action, $relation);

        return $action;
    }

    public static function getCreateAction(?string $relation = null): CreateAction
    {
        $createAction = CreateAction::make($relation);

        if (is_string($relation)) {
            static::applyActionRelationSchema($createAction, $relation, static::form(...));
        }

        static::configureCreateAction($createAction, $relation);

        return $createAction;
    }

    public static function configureCreateAction(Action $action, ?string $relation = null): Action
    {
        $action->modalHeading(static function (): string {
            $label = static::getTitleCaseModelLabel();

            return __('filament-actions::create.single.modal.heading', ['label' => $label]);
        });

        static::getModel() |> $action->model(...);

        static::configureAction($action, $relation);

        return $action;
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

        static::getRecordTitleAttribute() |> $action->recordTitleAttribute(...);

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
