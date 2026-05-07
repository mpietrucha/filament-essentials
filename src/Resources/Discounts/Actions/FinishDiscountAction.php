<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Actions;

use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Livewire\Component;
use Mpietrucha\Filament\Essentials\Actions\Concerns\HasRelation;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;

class FinishDiscountAction extends Action
{
    use HasRelation;

    protected function setUp(): void
    {
        parent::setUp();

        __('filament-essentials::discounts-plugin.actions.finish.label') |> $this->label(...);

        $this->icon(Heroicon::XMark);

        $this->color('danger');

        $this->requiresConfirmation();

        $this->hidden(static function (Discount $record, Component $livewire): bool {
            if ($livewire instanceof RelationManager && $livewire->isReadOnly()) {
                return true;
            }

            if ($record->isInactive()) {
                return true;
            }

            return $record->isFinished();
        });

        $this->action(static function (Discount $record, Component $livewire): void {
            $record->finish()->save();

            if ($livewire instanceof RelationManager) {
                return;
            }

            $livewire->js('$wire.$refresh()');
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'finish';
    }
}
