<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers\Concerns;

use Filament\Facades\Filament;
use Mpietrucha\Utility\Arr;
use Mpietrucha\Utility\Normalizer;
use Mpietrucha\Utility\Str;

/**
 * @phpstan-require-extends \Filament\Resources\RelationManagers\RelationManager
 */
trait GuessesResource
{
    public static function getRelatedResource(): string
    {
        if (static::$relatedResource) {
            return static::$relatedResource;
        }

        $name = Str::sprintf(
            '*%sResource',
            static::getRelationshipName() |> Str::singular(...)
        );

        $resource = Arr::first(
            Filament::getResources(),
            fn (string $resource) => Str::is($name, $resource)
        );

        return static::$relatedResource = Normalizer::string($resource);
    }
}
