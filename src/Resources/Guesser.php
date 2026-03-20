<?php

namespace Mpietrucha\Filament\Essentials\Resources;

use Filament\Facades\Filament;
use Illuminate\Support\Arr;
use Mpietrucha\Support\Str;

abstract class Guesser
{
    public static function guess(string $indicator): string
    {
        $name = sprintf(
            '*%s*Resource',
            $indicator |> Str::singular(...) |> Str::ucFirst(...)
        );

        $resource = Arr::first(
            Filament::getResources(),
            static fn (string $resource): bool => Str::is($name, $resource)
        );

        return (string) $resource;
    }
}
