<?php

namespace Mpietrucha\Filament\Essentials\Resources;

use Filament\Facades\Filament;
use Illuminate\Support\Arr;
use Mpietrucha\Filament\Essentials\Resources\Discounts\DiscountResource;
use Mpietrucha\Filament\Essentials\Resources\Translations\TranslationResource;
use Mpietrucha\Support\Str;

abstract class ResourceGuesser
{
    /**
     * @var array<class-string>
     */
    protected static array $resources = [
        DiscountResource::class,
        TranslationResource::class,
    ];

    public static function guess(string $indicator): string
    {
        $name = sprintf(
            '*%s*Resource',
            $indicator |> Str::singular(...) |> Str::ucFirst(...)
        );

        $resource = Arr::first(
            Filament::getResources() + static::$resources,
            static fn (string $resource): bool => Str::is($name, $resource)
        );

        return (string) $resource;
    }
}
