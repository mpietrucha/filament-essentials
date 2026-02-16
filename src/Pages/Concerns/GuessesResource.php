<?php

namespace Mpietrucha\Filament\Essentials\Pages\Concerns;

use Mpietrucha\Utility\Instance\Path;
use Mpietrucha\Utility\Str;

/**
 * @phpstan-require-extends \Filament\Resources\Pages\Page
 */
trait GuessesResource
{
    public static function getResource(): string
    {
        $namespace = Path::namespace(static::class, 2);

        $resource = Str::sprintf('%sResource', Path::name($namespace) |> Str::singular(...));

        /** @var class-string */
        return Path::join($namespace, $resource);
    }
}
