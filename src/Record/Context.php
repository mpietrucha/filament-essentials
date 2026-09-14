<?php

namespace Mpietrucha\Filament\Essentials\Record;

use Closure;
use Filament\Support\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Support\Concerns\Makeable;
use Mpietrucha\Support\Exception\RuntimeException;
use Mpietrucha\Support\Forward\Concerns\Forwardable;

/**
 * @internal
 */
abstract class Context
{
    use Forwardable;
    use Makeable;

    public function __construct(public readonly Model $record)
    {
    }

    public static function build(Component $component): static
    {
        $record = method_exists($component, 'getRecord') ? $component->getRecord() : null;

        if (! $record instanceof Model) {
            RuntimeException::throw('Unable to retrieve record from given component');
        }

        return static::make($record);
    }

    public static function pipe(Closure $handler): Closure
    {
        return static fn (Model $record): mixed => static::make($record) |> $handler;
    }
}
