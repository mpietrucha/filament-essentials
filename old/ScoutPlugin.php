<?php

namespace Mpietrucha\Filament\Essentials\Plugins;

use Filament\Contracts\Plugin as FilamentPlugin;
use Mpietrucha\Filament\Essentials\Plugins\Concerns\HasIdentifier;
use Mpietrucha\Support\Concerns\Makeable;

class ScoutPlugin implements FilamentPlugin
{
    use HasIdentifier;
    use Makeable;

    public function register(Panel $panel): void
    {
    }

    public function boot(Panel $panel): void
    {
    }
}
