<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers\Concerns;

use Mpietrucha\Filament\Essentials\Concerns\Identifiable;

/**
 * @phpstan-require-extends \Filament\Resources\RelationManagers\RelationManager
 */
trait GuessesRelationship
{
    use Identifiable;

    public static function getRelationshipName(): string
    {
        return static::$relationship ??= static::identify();
    }
}
