<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Actions;

use Filament\Actions\Action;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;

class FinishAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        __('filament-essentials::discounts-plugin.actions.finish.label') |> $this->label(...);

        $this->color('danger');

        $this->requiresConfirmation();

        $this->hidden(static function (Discount $record): bool {
            if ($record->isActive()) {
                return true;
            }

            return $record->isFinished();
        });

        $this->action(static function (Discount $record): void {
            $record->finish()->save();
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'finish';
    }
}
