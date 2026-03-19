<?php

namespace Mpietrucha\Filament\Essentials\Pages\Concerns;

use Filament\Resources\Pages\Page;
use Mpietrucha\Filament\Essentials\Concerns\Identifiable;
use Mpietrucha\Filament\Essentials\Resources\Guesser;

/**
 * @phpstan-require-extends Page
 *
 * @phpstan-import-type GuessedResource from Guesser
 */
trait GuessesResource
{
    use Identifiable;

    /**
     * @return GuessedResource
     */
    public static function getResource(): string
    {
        /** @var GuessedResource */
        return static::$resource ??= static::identify(null, 2) |> Guesser::guess(...);
    }
}
