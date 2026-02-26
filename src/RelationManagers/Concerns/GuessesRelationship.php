<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers\Concerns;

use Mpietrucha\Filament\Essentials\Name;

/**
 * @phpstan-require-extends \Filament\Resources\RelationManagers\RelationManager
 */
trait GuessesRelationship
{
    public static function getRelationshipName(): string
    {
        if (isset(static::$relationship)) {
            return static::$relationship;
        }

        return static::$relationship = Name::get(static::class, 'RelationManager');
    }
}
