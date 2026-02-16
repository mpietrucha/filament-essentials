<?php

namespace Mpietrucha\Filament\Essentials\Resources\Concerns;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Operation;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Utility\Data;
use Mpietrucha\Utility\Str;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-require-extends \Filament\Resources\Resource
 */
trait InteractsWithActions
{
    public static function getViewAction(?string $relation = null): Action|ViewAction
    {
        if (Type::null($relation)) {
            return ViewAction::make() |> static::configureViewAction(...);
        }

        $action = Action::make($relation);

        $action->infolist(fn (Model $record, Schema $schema) => static::infolist(
            static::getActionRecord($record, $relation) |> $schema->model(...)
        ));

        return static::configureViewAction($action, $relation);
    }

    public static function configureViewAction(Action|ViewAction $action, ?string $relation = null): Action|ViewAction
    {
        $action->modalSubmitAction(false);

        static::configureAction($action, Operation::View, $relation);

        return $action;
    }

    public static function getEditAction(): EditAction
    {
        return EditAction::make() |> static::configureEditAction(...);
    }

    public static function configureEditAction(EditAction $action): EditAction
    {
        static::configureAction($action, Operation::Edit);

        return $action;
    }

    public static function getCreateAction(): CreateAction
    {
        return CreateAction::make() |> static::configureCreateAction(...);
    }

    public static function configureCreateAction(CreateAction $action): CreateAction
    {
        static::configureAction($action, Operation::Create);

        return $action;
    }

    public static function configureAction(Action $action, Operation $operation, ?string $relation = null): Action
    {
        static::applyActionModalHeading($action, $operation, $relation);

        static::applyActionConfiguration($action);

        return $action;
    }

    public static function applyActionModalHeading(Action $action, Operation $operation, ?string $relation = null): void
    {
        $key = Str::sprintf('filament-actions::%s.single.modal.heading', $operation->value);

        $action->modalHeading(function (?Model $record) use ($key, $relation) {
            $label = match (true) {
                Type::null($record) => static::getTitleCaseModelLabel(),
                default => static::getActionRecord($record, $relation) |> static::getRecordTitle(...)
            };

            return __($key, compact('label'));
        });
    }

    public static function applyActionModalIcon(Action $action): void
    {
        $action->modalIconColor(Color::Gray);

        static::getNavigationIcon() |> $action->modalIcon(...);
    }

    public static function applyActionConfiguration(Action $action): void
    {
        static::applyActionModalIcon($action);

        static::applyDefaultActionConfiguration($action);
    }

    public static function applyDefaultActionConfiguration(Action $action): void
    {
    }

    protected static function getActionRecord(Model $record, ?string $relation = null): Model
    {
        if (Type::null($relation)) {
            return $record;
        }

        return Data::get($record, $relation);
    }
}
