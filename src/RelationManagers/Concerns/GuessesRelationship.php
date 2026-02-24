<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers\Concerns;

use Mpietrucha\Utility\Instance\Path;
use Mpietrucha\Utility\Str;

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

        $indicator = __CLASS__ |> Path::name(...);

        return static::$relationship = Str::of($indicator)->before('RelationManager')->lower();
    }
}
