<?php

namespace Mpietrucha\Filament\Essentials\Record;

use Filament\Infolists\Components\TextEntry;
use Filament\Support\Components\Component;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Support\Concerns\Compatible;
use Mpietrucha\Support\Forward\Concerns\Forwardable;
use Mpietrucha\Support\Str;

/**
 * @phpstan-import-type RecordComponent from Record
 */
class StateFormatter
{
    use Compatible;
    use Forwardable;

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
     * @param  RecordComponent  $component
     * @param  array<mixed>  $arguments
     */
    public static function format(Component $component, string $method, string $state, array $arguments): string
    {
        static::forward(
            $component = static::component($component)
        )->eval($method, $arguments);

        $value = $component->formatState($state);

        if (! is_scalar($value)) {
            $value = $state;
        }

        return (string) $value;
    }

    /**
     * @param  RecordComponent  $component
     */
    public static function component(Component $component): TextColumn|TextEntry
    {
        $name = Str::random(6);

        if ($component instanceof Column) {
            return TextColumn::make($name);
        }

        return $component->getContainer() |> TextEntry::make($name)->container(...);
    }
}
