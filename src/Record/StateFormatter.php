<?php

namespace Mpietrucha\Filament\Essentials\Record;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Mpietrucha\Support\Concerns\Compatible;
use Mpietrucha\Support\Forward\Concerns\Forwardable;
use Mpietrucha\Support\Reflection;
use Mpietrucha\Support\Str;

/**
 * @internal
 */
class StateFormatter
{
    use Compatible;
    use Forwardable;

    protected static ?TextColumn $adapter = null;

    /**
     * @var list<string>
     */
    protected static array $methods = [
        'date',
        'dateTime',
        'isoDate',
        'isoDateTime',
        'since',
        'money',
        'numeric',
        'time',
        'isoTime',
    ];

    public static function compatible(string $method): bool
    {
        $methods = static::$methods;

        return in_array($method, $methods);
    }

    /**
     * @param  array<mixed>  $arguments
     */
    public static function format(string $method, string $state, array $arguments): string
    {
        static::forward($adapter = static::adapter())->eval($method, $arguments);

        $value = $adapter->formatState($state);

        return is_scalar($value) ? (string) $value : $state;
    }

    public static function adapter(): TextColumn
    {
        if (static::$adapter instanceof TextColumn) {
            return static::$adapter;
        }

        $tableReflection = Reflection::make(Table::class);

        /** @var Table $table */
        $table = $tableReflection->newInstanceWithoutConstructor();

        $table->configure();

        $name = Str::random(6);

        return static::$adapter = TextColumn::make($name)->table($table);
    }
}
