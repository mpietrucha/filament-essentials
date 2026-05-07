<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Plugins;

use Filament\Contracts\Plugin as FilamentPlugin;
use Filament\Panel;
use Mpietrucha\Filament\Essentials\Plugins\Concerns\HasIdentifier;
use Mpietrucha\Filament\Essentials\Plugins\Concerns\HasResource;
use Mpietrucha\Support\Concerns\Makeable;

abstract class Plugin implements FilamentPlugin
{
    use HasIdentifier;
    use HasResource;
    use Makeable;

    public function register(Panel $panel): void
    {
    }

    public function boot(Panel $panel): void
    {
    }
}
