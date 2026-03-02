<?php

namespace Mpietrucha\Filament\Essentials\Pages\Concerns;

use Mpietrucha\Filament\Essentials\Concerns\Identifiable;
use Mpietrucha\Filament\Essentials\Resources\Guesser;

/**
 * @phpstan-require-extends \Filament\Resources\Pages\Page
 */
trait GuessesResource
{
    use Identifiable;

    protected static string $resource;

    public static function getResource(): string
    {
        return static::$resource ??= static::identify(null, 2) |> Guesser::guess(...);
    }
}
