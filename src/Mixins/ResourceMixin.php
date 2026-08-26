<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Closure;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Operation;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Actions\Concerns\ResolvesRecordFromRelationship;
use Mpietrucha\Filament\Essentials\Actions\CreateAction;
use Mpietrucha\Filament\Essentials\Actions\EditAction;
use Mpietrucha\Filament\Essentials\Actions\ViewAction;
use Mpietrucha\Support\Instance;

/**
 * @phpstan-require-extends Resource
 */
trait ResourceMixin
{
    public static function getViewAction(?string $relationship = null): ViewAction
    {
        $viewAction = ViewAction::make($relationship);

        if (is_string($relationship)) {
            static::configureActionRelation($viewAction, $relationship, static::infolist(...));
        }

        static::configureViewAction($viewAction, $relationship);

        return $viewAction;
    }

    public static function configureViewAction(Action $action, ?string $relationship = null): Action
    {
        Operation::View |> $action->operation(...);

        static::configureAction($action, $relationship);

        if ($action instanceof ViewAction) {
            $action->withFormActionsResource(static::class);
        }

        return $action;
    }

    public static function getEditAction(?string $relationship = null): EditAction
    {
        $editAction = EditAction::make($relationship);

        if (is_string($relationship)) {
            static::configureActionRelation($editAction, $relationship);
        }

        static::configureEditAction($editAction, $relationship);

        return $editAction;
    }

    public static function configureEditAction(Action $action, ?string $relationship = null): Action
    {
        Operation::Edit |> $action->operation(...);

        static::configureAction($action, $relationship);

        return $action;
    }

    public static function getCreateAction(?string $relationship = null): CreateAction
    {
        $createAction = CreateAction::make($relationship);

        if (is_string($relationship)) {
            static::configureActionRelation($createAction, $relationship);
        }

        static::configureCreateAction($createAction, $relationship);

        return $createAction;
    }

    public static function configureCreateAction(Action $action, ?string $relationship = null): Action
    {
        Operation::Create |> $action->operation(...);

        static::configureAction($action, $relationship);

        $action->modalHeading(static function (): string {
            $label = static::getTitleCaseModelLabel();

            return __('filament-actions::create.single.modal.heading', ['label' => $label]);
        });

        static::getModel() |> $action->model(...);

        return $action;
    }

    public static function configureAction(Action $action, ?string $relationship = null): Action
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
    public static function configureActionRelation(Action $action, string $relationship, ?Closure $resourceSchema = null): Action
    {
        if (Instance::traits($action)->contains(ResolvesRecordFromRelationship::class)) {
            /** @phpstan-ignore method.notFound */
            $action->relationship($relationship);
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
