<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers\Concerns;

use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Actions\RelationManagerAction;
use Illuminate\Database\Eloquent\Model;

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

    public static function getEditAction(): RelationManagerAction
    {
        $action = RelationManagerAction::make();

        static::make() |> $action->relationManager(...);

        return static::configureEditAction($action);
    }

    public static function configureEditAction(RelationManagerAction $action): RelationManagerAction
    {
        $action->hideRelationManagerHeading(false);

        $action->compact();

        static::getRelatedResource()::applyActionModalIcon($action);

        $action->label(function (Model $record, Page $livewire) {
            $title = $livewire::getResource()::getRecordTitleAttribute();

            $record = $record->$title;

            $records = static::getRelatedResource()::getPluralModelLabel();

            return __('filament-essentials::actions.relation_managers.modal.label', compact('records', 'record'));
        });

        return $action;
    }

    public static function getCreateAction(): CreateAction
    {
        return static::getRelatedResource()::getCreateAction() |> static::configureCreateAction(...);
    }

    public static function configureCreateAction(CreateAction $action): CreateAction
    {
        $action->hidden(fn (self $livewire) => $livewire->isModal());

        return $action;
    }

    public static function getAttachAction(): AttachAction
    {
        return AttachAction::make() |> static::configureAttachAction(...);
    }

    public static function configureAttachAction(AttachAction $action): AttachAction
    {
        $action->preloadRecordSelect();

        return $action;
    }
}
