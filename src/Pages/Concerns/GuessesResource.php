<?php

namespace Mpietrucha\Filament\Essentials\Pages\Concerns;

use Mpietrucha\Filament\Essentials\Resources\Guesser;
use Mpietrucha\Utility\Instance\Path;

/**
 * @phpstan-require-extends \Filament\Resources\Pages\Page
 */
trait GuessesResource
{
    protected static string $resource;

    public static function getResource(): string
    {
        if (isset(static::$resource)) {
            /** @var class-string */
            return static::$resource;
        }

        $namespace = Path::namespace(__CLASS__, 2);

        return static::$resource = Path::name($namespace) |> Guesser::guess(...);
    }
}
