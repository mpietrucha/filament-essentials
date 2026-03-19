<?php

namespace Mpietrucha\Filament\Essentials;

use Closure;
use Filament\Schemas\Components\Component;
use Filament\Tables\Columns\Column;
use Mpietrucha\Filament\Essentials\Record\Adapter;
use Mpietrucha\Filament\Essentials\Record\Context;

/**
 * @phpstan-type RecordComponent Component|Column
 *
 * @mixin Adapter
 */
class Record extends Context
{
    protected ?Adapter $adapter = null;

    /**
     * @param  array<mixed>  $arguments
     */
    public static function __callStatic(string $method, array $arguments): Closure
    {
        return static::pipe(static fn (self $record): mixed => static::forward($record)->eval(
            $method,
            $arguments
        ));
    }

    /**
     * @param  array<mixed>  $arguments
     */
    public function __call(string $method, array $arguments): mixed
    {
        return static::forward(
            $this->adapter()
        )->eval($method, $arguments);
    }

    public static function attribute(string $attribute, ?string $relation = null): string
    {
        if ($relation === null) {
            return $attribute;
        }

        return sprintf('%s.%s', $relation, $attribute);
    }

    public function adapter(): Adapter
    {
        return $this->adapter ??= Adapter::make(...$this->toArray());
    }
}
