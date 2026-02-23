<?php

namespace Mpietrucha\Filament\Essentials;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Record\Context;
use Mpietrucha\Utility\Forward\Concerns\Bridgeable;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 *
 *  @mixin \Mpietrucha\Filament\Essentials\Record\Context
 */
abstract class Record
{
    use Bridgeable;

    /**
     * @param  MixedArray  $arguments
     */
    public static function __callStatic(string $method, array $arguments): Closure
    {
        return function (Model $record) use ($method, $arguments) {
            $context = static::context($record);

            return static::bridge($context)->eval($method, $arguments);
        };
    }

    public static function context(Model $record): Context
    {
        return Context::create($record);
    }
}
