<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Actions;

use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Actions\Concerns\TransformsRecord;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;

class FinishDiscountAction extends Action
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

            /** @var null|Discount $record */
            $record = $this->getTransformedRecord($record);

            if (! $record instanceof Model) {
                return true;
            }

            if ($record->isInactive()) {
                return true;
            }

            return $record->isFinished();
        });

        $this->action(function (Model $record): void {
            /** @var null|Discount $record */
            $record = $this->getTransformedRecord($record);

            if (! $record instanceof Model) {
                return;
            }

            $record->finish()->save();

            /** @phpstan-ignore method.notFound */
            $this->getLivewire()?->js('$wire.$refresh()');
        });
    }

    public static function related(string $relationship): static
    {
        $finishDiscountAction = static::make();

        __('filament-essentials::discounts-plugin.actions.finish.extended_label') |> $finishDiscountAction->label(...);

        return $finishDiscountAction->relationship($relationship);
    }

    public static function getDefaultName(): ?string
    {
        return 'finish';
    }
}
