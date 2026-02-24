<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers\Concerns;

use Mpietrucha\Filament\Essentials\Resources\Guesser;

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

        return static::$relatedResource = static::getRelationshipName() |> Guesser::guess(...);
    }
}
