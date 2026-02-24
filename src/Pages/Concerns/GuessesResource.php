<?php

namespace Mpietrucha\Filament\Essentials\Pages\Concerns;

use Mpietrucha\Filament\Essentials\Resources\Guesser;
use Mpietrucha\Utility\Instance\Path;

/**
 * @phpstan-require-extends \Filament\Resources\Pages\Page
 */
trait GuessesResource
{
    public static function getResource(): string
    {
        if (isset(static::$resource)) {
            /** @var class-string */
            return static::$resource;
        }

        return static::$resource = Path::name(__CLASS__) |> Guesser::guess(...);
    }
}
