<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers\Concerns;

use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource as FilamentResource;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Actions\RelationManagerAction;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Resources\Concerns\InteractsWithActions as ResourceActions;
use Mpietrucha\Support\Exception\RuntimeException;
use Mpietrucha\Support\Instance;
use Mpietrucha\Support\Str;

/**
 * @phpstan-require-extends RelationManager
 */
trait InteractsWithActions
{
    public function isModal(): bool
    {
        return is_a($this->getPageClass(), ListRecords::class, true);
    }

    public function getTable(): Table
    {
        $table = parent::getTable();

        if ($this->isModal()) {
            $table->recordAction(null);
        }

        return $table;
    }

    /**
     * @return class-string<FilamentResource>
     */
    public static function getDecoratedActionsResource(): string
    {
        $resource = static::getRelatedResource();

        if ($resource === null) {
            RuntimeException::throw('RelationManager resource is required');
        }

        if (Instance::traits($resource)->doesntContain(ResourceActions::class)) {
            RuntimeException::throw('RelationManager resource must use `%s` trait', ResourceActions::class);
        }

        /** @var class-string<FilamentResource> $resource */
        return $resource;
    }

    public static function getEditAction(): RelationManagerAction
    {
        $relationManagerAction = RelationManagerAction::make();

        static::make() |> $relationManagerAction->relationManager(...);

        return static::configureEditAction($relationManagerAction);
    }

    public static function configureEditAction(RelationManagerAction $relationManagerAction): RelationManagerAction
    {
        $relationManagerAction->hideRelationManagerHeading(false);

        $relationManagerAction->compact();

        /** @phpstan-ignore staticMethod.notFound */
        static::getDecoratedActionsResource()::applyActionModalIcon($relationManagerAction);

        $relationManagerAction->label(static function (Model $record, Page $page): string {
            $title = $page::getResource()::getRecordTitleAttribute();

            $record = $record->$title;

            if (! is_string($record)) {
                $record = Str::none();
            }

            $records = static::getDecoratedActionsResource()::getPluralModelLabel();

            return __('filament-essentials::relation-managers.modal_label', ['records' => $records, 'record' => $record]);
        });

        return $relationManagerAction;
    }

    public static function getCreateAction(): CreateAction
    {
        /** @phpstan-ignore staticMethod.notFound, argument.type */
        return static::getDecoratedActionsResource()::getCreateAction() |> static::configureCreateAction(...);
    }

    public static function configureCreateAction(CreateAction $createAction): CreateAction
    {
        $createAction->hidden(static function (self $livewire): bool {
            return $livewire->isModal();
        });

        return $createAction;
    }

    public static function getAttachAction(): AttachAction
    {
        return AttachAction::make() |> static::configureAttachAction(...);
    }

    public static function configureAttachAction(AttachAction $attachAction): AttachAction
    {
        $attachAction->preloadRecordSelect();

        return $attachAction;
    }
}
