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

        __('filament-essentials::discounts-plugin.action.finish.label') |> $this->label(...);

        $this->icon(Heroicon::XMark);

        $this->color('danger');

        $this->requiresConfirmation();

        $this->hidden(static function (Discount $discount, Component $livewire): bool {
            if ($livewire instanceof RelationManager && $livewire->isReadOnly()) {
                return true;
            }

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

    public static function getDefaultName(): ?string
    {
        return 'finish';
    }
}
