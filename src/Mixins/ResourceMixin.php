<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Closure;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Actions\Concerns\HasRelation;
use Mpietrucha\Filament\Essentials\Actions\CreateAction;
use Mpietrucha\Filament\Essentials\Actions\EditAction;
use Mpietrucha\Filament\Essentials\Actions\ViewAction;
use Mpietrucha\Support\Instance;

/**
 * @phpstan-require-extends Resource
 */
trait ResourceMixin
{
    public static function getViewAction(?string $relation = null): ViewAction
    {
        $viewAction = ViewAction::make($relation);

        if (is_string($relation)) {
            static::configureActionRelation($viewAction, $relation, static::infolist(...));
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
            static::configureActionRelation($editAction, $relation);
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
            static::configureActionRelation($createAction, $relation);
        }

        static::configureCreateAction($createAction, $relation);

        return $createAction;
    }

    public static function configureCreateAction(Action $action, ?string $relation = null): Action
    {
        static::configureAction($action, $relation);

        $action->modalHeading(static function (): string {
            $label = static::getTitleCaseModelLabel();

            return __('filament-actions::create.single.modal.heading', ['label' => $label]);
        });

        static::getModel() |> $action->model(...);

        return $action;
    }

    public static function configureAction(Action $action, ?string $relation = null): Action
    {
        $action->slideOver();

        $action->modalWidth(Width::Medium);

        $action->modalIconColor(Color::Gray);

        static::getNavigationIcon() |> $action->modalIcon(...);

        static::getRecordTitleAttribute() |> $action->recordTitleAttribute(...);

        return $action;
    }

    /**
     * @param  null|Closure(Schema): Schema  $resourceSchema
     */
    public static function configureActionRelation(Action $action, string $relation, ?Closure $resourceSchema = null): Action
    {
        if (Instance::traits($action)->contains(HasRelation::class)) {
            /** @phpstan-ignore method.notFound */
            $action->relation($relation);
        }

        static::configureActionSchema($action, $resourceSchema);

        return $action;
    }

    /**
     * @param  null|Closure(Schema): Schema  $resourceSchema
     */
    public static function configureActionSchema(Action $action, ?Closure $resourceSchema = null): Action
    {
        $resourceSchema ??= static::form(...);

        $action->schema(static function (?Model $record, Schema $schema) use ($resourceSchema): Schema {
            $model = $record ?? static::getModel();

            return $schema->model($model) |> $resourceSchema;
        });

        return $action;
    }
}
