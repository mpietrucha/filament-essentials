<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Actions;

use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Livewire\Component;
use Mpietrucha\Filament\Essentials\Actions\Concerns\ResolvesRecordFromRelation;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;

class FinishDiscountAction extends Action
{
    use ResolvesRecordFromRelation;

    protected function setUp(): void
    {
        parent::setUp();

        __('filament-essentials::discounts-plugin.action.finish.label') |> $this->label(...);

        $this->icon(Heroicon::XMark);

        $this->color('danger');

        $this->requiresConfirmation();

        $this->hidden(static function (Discount $discount): bool {
            if ($discount->isInactive()) {
                return true;
            }

            return $discount->isFinished();
        });

        $this->action(static function (Discount $discount, Component $livewire): void {
            $discount->finish()->save();

            if ($livewire instanceof RelationManager) {
                return;
            }

            $livewire->js('$wire.$refresh()');
        });
    }

    public static function getDefaultName(): string
    {
        return 'finish-discount';
    }
}
