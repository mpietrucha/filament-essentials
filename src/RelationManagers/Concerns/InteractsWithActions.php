<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers\Concerns;

use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\Page;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Actions\RelationManagerAction;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Utility\Instance;

/**
 * @phpstan-require-extends \Filament\Resources\RelationManagers\RelationManager
 */
trait InteractsWithActions
{
    public function isModal(): bool
    {
        $list = \Filament\Resources\Pages\ListRecords::class;

        $page = $this->getPageClass();

        return Instance::is($page, $list);
    }

    public function getTable(): Table
    {
        $table = parent::getTable();

        $this->isModal() && $table->recordAction(null);

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
            return __(':Records for :record', [
                'records' => static::getRelatedResource()::getPluralModelLabel(),
                'record' => $record->{$livewire::getResource()::getRecordTitleAttribute()},
            ]);
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

    public static function configureAttachAction(AttachAction $action): Action
    {
        $action->preloadRecordSelect();

        return $action;
    }
}
