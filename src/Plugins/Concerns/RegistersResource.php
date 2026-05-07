<?php

namespace Mpietrucha\Filament\Essentials\Plugins\Concerns;

use Filament\Contracts\Plugin;
use Filament\Panel;

/**
 * @phpstan-require-implements Plugin
 */
trait RegistersResource
{
    use HasResource;

    public function register(Panel $panel): void
    {
        $this->registerResource($panel);
    }
}
