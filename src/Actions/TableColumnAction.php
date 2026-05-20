<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Actions;

use Closure;
use Filament\Actions\Action;
use Mpietrucha\Support\Exception\RuntimeException;

class TableColumnAction extends Action
{
    protected ?Closure $resolveActionUsing = null;

    public static function resolve(Action $action): Action
    {
        if (! $action instanceof self) {
            return $action;
        }

        return $action->getResolvedAction();
    }

    public function resolveActionUsing(Closure $resolveActionUsing): static
    {
        $this->resolveActionUsing = $resolveActionUsing;

        return $this;
    }

    public function getResolvedAction(): Action
    {
        $action = $this->resolveActionUsing |> $this->evaluate(...);

        if (! $action instanceof Action) {
            RuntimeException::throw('Table column action must resolve to Action instance');
        }

        $this->getName() |> $action->name(...);
        $this->getTable() |> $action->table(...);
        $this->getRecord() |> $action->record(...);

        return $action;
    }
}
