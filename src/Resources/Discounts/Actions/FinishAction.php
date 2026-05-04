<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Actions;

use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Actions\Concerns\TransformsRecord;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;

class FinishAction extends Action
{
    use TransformsRecord;

    protected function setUp(): void
    {
        parent::setUp();

        __('filament-essentials::discounts-plugin.actions.finish.label') |> $this->label(...);

        $this->icon(Heroicon::XMark);

        $this->color('danger');

        $this->requiresConfirmation();

        $this->hidden(function (Model $record): bool {
            $livewire = $this->getLivewire();

            if ($livewire instanceof RelationManager && $livewire->isReadOnly()) {
                return true;
            }

            /** @var Discount $record */
            $record = $this->getTransformedRecord($record);

            if ($record->isInactive()) {
                return true;
            }

            return $record->isFinished();
        });

        $this->action(function (Model $record): void {
            /** @var Discount $record */
            $record = $this->getTransformedRecord($record);

            $record->finish()->save();

            /** @phpstan-ignore method.notFound */
            $this->getLivewire()?->js('$wire.$parent.$refresh()');
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'finish';
    }
}
