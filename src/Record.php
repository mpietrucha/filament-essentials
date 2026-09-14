<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials;

use Closure;
use Mpietrucha\Filament\Essentials\Record\Adapter;
use Mpietrucha\Filament\Essentials\Record\Context;

/**
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
            $arguments,
        ));
    }

    /**
     * @param  array<mixed>  $arguments
     */
    public function __call(string $method, array $arguments): mixed
    {
        $adapter = $this->adapter();

        return static::forward($adapter)->eval($method, $arguments);
    }

    public function adapter(): Adapter
    {
        if ($this->adapter instanceof Adapter) {
            return $this->adapter;
        }

        return $this->adapter = $this->record |> Adapter::make(...);
    }
}
