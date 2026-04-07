<?php

namespace Mpietrucha\Filament\Essentials\Plugins\Concerns;

use Filament\Contracts\Plugin;
use Mpietrucha\Filament\Essentials\Concerns\Identifiable;

/**
 * @phpstan-require-implements Plugin
 */
trait HasIdentifier
{
    use Identifiable;

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = static::identifier() |> filament(...);

        return $plugin;
    }

    public function getId(): string
    {
        return static::identifier();
    }

    protected static function identifier(): string
    {
        $name = static::identify('Plugin');

        return sprintf('mpietrucha-essentials-filament-%s-plugin', $name);
    }
}
