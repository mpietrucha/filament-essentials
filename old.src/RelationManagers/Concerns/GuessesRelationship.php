<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers\Concerns;

use Filament\Resources\RelationManagers\RelationManager;
use Mpietrucha\Filament\Essentials\Concerns\Identifiable;

/**
 * @phpstan-require-extends RelationManager
 */
trait GuessesRelationship
{
    use Identifiable;

    public static function getRelationshipName(): string
    {
        return static::$relationship ??= static::identify('RelationManager');
    }
}
