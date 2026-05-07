<?php

namespace Mpietrucha\Filament\Essentials\Plugins\Concerns;

use Filament\Panel;
use Mpietrucha\Filament\Essentials\Plugins\Plugin;

/**
 * @phpstan-require-extends Plugin
 */
trait RegistersResource
{
    public function register(Panel $panel): void
    {
        $this->registerResource($panel);
    }
}
