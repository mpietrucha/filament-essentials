<?php

namespace Mpietrucha\Filament\Essentials\Actions\Concerns;

use Filament\Actions\Action;

/**
 * @phpstan-require-extends Action
 */
trait HasRelation
{
    public function relation(string $relation): static
    {
        return $this;
    }
}
