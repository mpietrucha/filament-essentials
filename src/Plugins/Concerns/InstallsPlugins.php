<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Plugins\Concerns;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Mpietrucha\Support\Exception\InvalidArgumentException;

/**
 * @phpstan-require-implements Plugin
 */
trait InstallsPlugins
{
    public static function install(Closure|Plugin $plugin, Panel $panel, bool|Closure $install = true): bool
    {
        if ($install === false) {
            return false;
        }

        $plugin = value($plugin);

        if (! $plugin instanceof Plugin) {
            InvalidArgumentException::throw('Install plugin must implement %s', Plugin::class);
        }

        value($install, $plugin);
        $panel->plugin($plugin);

        return true;
    }
}
