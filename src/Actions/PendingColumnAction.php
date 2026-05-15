<?php

namespace Mpietrucha\Filament\Essentials\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Mpietrucha\Support\Exception\RuntimeException;

class PendingColumnAction extends Action
{
    protected ?Closure $resolver = null;

    public function resolver(Closure $resolver): static
    {
        $this->resolver = $resolver;

        return $this;
    }

    public function getResolver(): ?Closure
    {
        return $this->resolver;
    }

    #[\Override]
    public function table(?Table $table): static
    {
        if (! $table instanceof Table) {
            return $this;
        }

        $action = $this->getResolver() |> $table->evaluate(...);

        if (! $action instanceof Action) {
            RuntimeException::throw('Action resolver must return Action instance');
        }

        /** @phpstan-ignore return.type */
        return $action;
    }
}
