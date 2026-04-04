<?php

namespace Mpietrucha\Filament\Essentials\Plugins;

use Filament\Panel;
use Mpietrucha\Filament\Essentials\Plugins\Concerns\HasIdentifier;
use Mpietrucha\Filament\Essentials\Plugins\Concerns\InstallsPlugins;
use Mpietrucha\Support\Concerns\Makeable;

abstract class Plugin implements \Filament\Contracts\Plugin
{
    use HasIdentifier;
    use InstallsPlugins;
    use Makeable;

    public function register(Panel $panel): void
    {
    }

    public function boot(Panel $panel): void
    {
    }
}
