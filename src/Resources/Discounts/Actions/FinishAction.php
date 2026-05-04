<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Actions;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Livewire\Component;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;

class FinishAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        __('filament-essentials::discounts-plugin.actions.finish.label') |> $this->label(...);

        $this->icon(Heroicon::XMark);

        $this->color('danger');

        $this->requiresConfirmation();

        $this->hidden(static function (Discount $record, Component $livewire): bool {
            if ($livewire->isReadOnly()) { /** @phpstan-ignore method.notFound */
                return true;
            }

            if ($record->isInactive()) {
                return true;
            }

            return $record->isFinished();
        });

        $this->action(static function (Discount $record, Component $livewire): void {
            $record->finish()->save();

            $livewire->js('$wire.$parent.$refresh()');
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'finish';
    }
}
