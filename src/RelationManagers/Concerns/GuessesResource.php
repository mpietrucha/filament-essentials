<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers\Concerns;

use Filament\Resources\RelationManagers\RelationManager;
use Mpietrucha\Filament\Essentials\Resources\Guesser;

/**
 * @phpstan-require-extends RelationManager
 *
 * @phpstan-import-type GuessedResource from Guesser
 */
trait GuessesResource
{
    /**
     * @return GuessedResource
     */
    public static function getRelatedResource(): string
    {
        if (static::$relatedResource) {
            /** @var GuessedResource */
            return static::$relatedResource;
        }

        return static::$relatedResource = static::getRelationshipName() |> Guesser::guess(...);
    }
}
