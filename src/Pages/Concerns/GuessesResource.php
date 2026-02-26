<?php

namespace Mpietrucha\Filament\Essentials\Pages\Concerns;

use Mpietrucha\Filament\Essentials\Instance;
use Mpietrucha\Filament\Essentials\Resources\Guesser;

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

        return static::$resource = Instance::name(__CLASS__, level: 2) |> Guesser::guess(...);
    }
}
