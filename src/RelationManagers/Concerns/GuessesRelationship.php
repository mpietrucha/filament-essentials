<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers\Concerns;

use Mpietrucha\Utility\Str;

/**
 * @phpstan-require-extends \Filament\Resources\RelationManagers\RelationManager
 */
trait GuessesRelationship
{
    public static function getRelationshipName(): string
    {
        return static::$relationship ??= Str::of(static::class)->classBasename()->before('RelationManager')->lower();
    }
}
