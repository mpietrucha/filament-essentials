<?php

namespace Mpietrucha\Filament\Essentials\Resources\Concerns;

use Mpietrucha\Filament\Essentials\Concerns\Identifiable;
use Mpietrucha\Filament\Essentials\Resources\ResourceGuesser;

trait GuessesResource
{
    use Identifiable;

    /**
     * @var class-string
     */
    protected static string $resource;

    public static function getResource(): string
    {
        /** @var class-string */
        return static::$resource ??= static::identify(level: 2) |> ResourceGuesser::guess(...);
    }
}
