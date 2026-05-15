<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Actions;

use Closure;
use Filament\Actions\Action;

class PendingColumnAction extends Action
{
    protected ?Closure $actionResolver = null;

    public function actionResolver(Closure $actionResolver): static
    {
        $this->actionResolver = $actionResolver;

        return $this;
    }

    public function getActionResolver(): ?Closure
    {
        return $this->actionResolver;
    }
}
