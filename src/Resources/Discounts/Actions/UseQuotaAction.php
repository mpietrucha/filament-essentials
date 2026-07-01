<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Actions;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Livewire\Component;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount\Quota;

class UseQuotaAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        __('filament-essentials::discounts-plugin.action.increment_quota_usage.label') |> $this->label(...);

        $this->icon(Heroicon::Plus);

        $this->color('warning');

        $this->cancelParentActions();

        $this->hidden(static function (?Quota $record): bool {
            if (! $record instanceof Quota) {
                return true;
            }

            if ($record->isUnlimited()) {
                return true;
            }

            return $record->isExceeded();
        });

        $this->action(static function (Quota $record, Component $livewire): void {
            $record->use()->save();

            $livewire->refresh();
        });
    }

    public static function getDefaultName(): string
    {
        return 'use-discount-quota';
    }
}
