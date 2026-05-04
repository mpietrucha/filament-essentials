<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Actions;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Actions\Concerns\HasRelationship;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;

class FinishAction extends Action
{
    use HasRelationship;

    protected function setUp(): void
    {
        parent::setUp();

        __('filament-essentials::discounts-plugin.actions.finish.label') |> $this->label(...);

        $this->icon(Heroicon::XMark);

        $this->color('danger');

        $this->requiresConfirmation();

        $this->hidden(function (Model $record): bool {
            if ($this->getLivewire()?->isReadOnly()) { /** @phpstan-ignore method.notFound */
                return true;
            }

            /** @var Discount $record */
            $record = $this->getRelatedRecord($record);

            if ($record->isInactive()) {
                return true;
            }

            return $record->isFinished();
        });

        $this->action(function (Model $record): void {
            /** @var Discount $record */
            $record = $this->getRelatedRecord($record);

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
