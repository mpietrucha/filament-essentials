<?php

namespace Mpietrucha\Filament\Essentials\Plugins\Concerns;

use Filament\Contracts\Plugin as FilamentPlugin;
use Mpietrucha\Filament\Essentials\Concerns\Identifiable;

/**
 * @phpstan-require-implements FilamentPlugin
 */
trait HasIdentifier
{
    use Identifiable;

    protected static ?string $identifier = null;

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
        if (static::$identifier) {
            return static::$identifier;
        }

        $name = static::identify('Plugin');

        return static::$identifier = sprintf('mpietrucha-essentials-filament-%s-plugin', $name);
    }
}
