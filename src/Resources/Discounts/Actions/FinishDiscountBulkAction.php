<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Actions;

use Filament\Actions\BulkAction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;

class FinishDiscountBulkAction extends BulkAction
{
    protected function setUp(): void
    {
        parent::setUp();

        __('filament-essentials::discounts-plugin.action.bulk_finish.label') |> $this->label(...);

        $this->icon(Heroicon::XMark);

        $this->color('danger');

        $this->requiresConfirmation();

        $this->deselectRecordsAfterCompletion();

        /** @phpstan-ignore argument.type */
        $this->action(static fn (Collection $records) => $records->each(static function (Model $record): void {
            /** @phpstan-ignore property.notFound */
            $discount = $record->discount;

            if (! $discount instanceof Discount) {
                return;
            }

            $discount->finish()->save();
        }));
    }

    public static function getDefaultName(): string
    {
        return 'finish-discount-bulk';
    }
}
