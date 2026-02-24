<?php

namespace Mpietrucha\Filament\Essentials\Resources;

use Filament\Facades\Filament;
use Mpietrucha\Utility\Arr;
use Mpietrucha\Utility\Normalizer;
use Mpietrucha\Utility\Str;

abstract class Guesser
{
    /**
     * @return class-string
     */
    public static function guess(string $indicator): string
    {
        $name = Str::sprintf(
            '*%s*Resource',
            $indicator |> Str::singular(...) |> Str::ucFirst(...)
        );

        $resource = Arr::first(
            Filament::getResources(),
            fn (string $resource) => Str::is($name, $resource)
        );

        /** @var class-string */
        return Normalizer::string($resource);
    }
}
