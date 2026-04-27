<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Plugins\Concerns;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Mpietrucha\Support\Exception\InvalidArgumentException;

/**
 * @phpstan-require-implements Plugin
 */
trait RegistersPlugin
{
    protected static function registerPlugin(Panel $panel, mixed $plugin, mixed $install = true): bool
    {
        if ($install === false) {
            return false;
        }

        $plugin = value($plugin);

        if (! $plugin instanceof Plugin) {
            InvalidArgumentException::throw('The plugin to be registered must implement %s', Plugin::class);
        }

        value($install, $plugin);

        $panel->plugin($plugin);

        return true;
    }
}
