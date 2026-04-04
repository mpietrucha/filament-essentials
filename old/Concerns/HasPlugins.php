<?php

namespace Mpietrucha\Filament\Essentials\Plugins\Concerns;

use Closure;
use Filament\Contracts\Plugin as FilamentPlugin;
use Filament\Panel;

trait HasPlugins
{
    protected static function plugin(Panel $panel, object $plugin, bool|Closure $callback = true): bool
    {
        if ($callback === false) {
            return false;
        }

        $plugin = value($plugin);

        if (! $plugin instanceof FilamentPlugin) {
            return false;
        }

        value($callback, $plugin);

        $panel->plugin($plugin);

        return true;
    }
}
