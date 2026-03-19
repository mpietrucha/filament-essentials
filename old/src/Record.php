<?php

namespace Mpietrucha\Filament\Essentials;

use Closure;
use Mpietrucha\Filament\Essentials\Record\Adapter;
use Mpietrucha\Filament\Essentials\Record\Evaluation;
use Mpietrucha\Utility\Str;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 *
 * @phpstan-type RecordComponent \Filament\Schemas\Components\Component|\Filament\Tables\Columns\Column
 *
 * @mixin \Mpietrucha\Filament\Essentials\Record\Adapter
 */
class Record extends Evaluation
{
    protected ?Adapter $adapter = null;

    /**
     * @param  MixedArray  $arguments
     */
    public static function __callStatic(string $method, array $arguments): Closure
    {
        return static::bind(function (self $record) use ($method, $arguments) {
            $bridge = static::bridge($record);

            return $bridge->eval($method, $arguments);
        });
    }

    /**
     * @param  MixedArray  $arguments
     */
    public function __call(string $method, array $arguments): mixed
    {
        $adapter = $this->adapter();

        return static::bridge($adapter)->eval($method, $arguments);
    }

    public static function attribute(string $attribute, ?string $relation = null): string
    {
        if (Type::null($relation)) {
            return $attribute;
        }

        return Str::sprintf('%s.%s', $relation, $attribute);
    }

    public function adapter(): Adapter
    {
        return $this->adapter ??= Adapter::replicate($this);
    }
}
