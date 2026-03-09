<?php

namespace Mpietrucha\Filament\Essentials;

use Closure;
use Filament\Support\Components\Component;
use Mpietrucha\Filament\Essentials\Record\Adapter;
use Mpietrucha\Filament\Essentials\Record\Concerns\InteractsWithComponent;
use Mpietrucha\Utility\Concerns\Creatable;
use Mpietrucha\Utility\Contracts\CreatableInterface;
use Mpietrucha\Utility\Forward\Concerns\Bridgeable;
use Mpietrucha\Utility\Value;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 *
 * @phpstan-type RecordComponent \Filament\Schemas\Components\Component|\Filament\Tables\Columns\Column
 *
 * @mixin \Mpietrucha\Filament\Essentials\Record\Adapter
 */
class Record implements CreatableInterface
{
    use Bridgeable, Creatable, InteractsWithComponent;

    protected ?Adapter $adapter = null;

    /**
     * @param  MixedArray  $arguments
     */
    public static function __callStatic(string $method, array $arguments): Closure
    {
        return static::use(function (self $record) use ($method, $arguments) {
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

    /**
     * @return \Closure(RecordComponent): mixed
     */
    public static function use(Closure $handler): Closure
    {
        return /** @param RecordComponent $component **/ function (Component $component) use ($handler) {
            $record = static::create($component);

            return Value::for($handler)->get($record);
        };
    }

    public function adapter(): Adapter
    {
        return $this->adapter ??= $this->component() |> Adapter::create(...);
    }
}
