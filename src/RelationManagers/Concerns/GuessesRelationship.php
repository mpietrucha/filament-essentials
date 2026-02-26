<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers\Concerns;

use Mpietrucha\Filament\Essentials\Instance;

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

        return static::$relationship = Instance::name(__CLASS__, 'RelationManager');
    }
}
