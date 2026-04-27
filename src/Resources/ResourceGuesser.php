<?php

namespace Mpietrucha\Filament\Essentials\Resources;

use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Support\Arr;
use Mpietrucha\Support\Str;

/**
 * @phpstan-type FilamentResource class-string<Resource>
 */
abstract class ResourceGuesser
{
    /**
     * @var array<FilamentResource>
     */
    protected static array $guessableResources = [];

    /**
     * @param  FilamentResource  $resource
     */
    public static function registerGuessableResource(string $resource): void
    {
        static::$guessableResources[] = $resource;
    }

    /**
     * @return array<FilamentResource>
     */
    public static function getGuessableResources(): array
    {
        /** @var array<FilamentResource> $resources */
        $resources = Filament::getResources();

        return [
            ...$resources,
            ...static::$guessableResources,
        ];
    }

    public static function guess(string $indicator): string
    {
        $name = sprintf(
            '*%s*Resource',
            $indicator |> Str::singular(...) |> Str::ucFirst(...)
        );

        $resource = Arr::first(
            static::getGuessableResources(),
            static fn (string $resource): bool => Str::is($name, $resource),
        );

        return (string) $resource;
    }
}
