<?php

namespace Mpietrucha\Filament\Essentials\Record;

use Filament\Infolists\Components\TextEntry;
use Filament\Support\Components\Component;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Mpietrucha\Utility\Arr;
use Mpietrucha\Utility\Concerns\Compatible;
use Mpietrucha\Utility\Contracts\CompatibleInterface;
use Mpietrucha\Utility\Forward\Concerns\Bridgeable;
use Mpietrucha\Utility\Str;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 * @phpstan-import-type EvaluationComponent from \Mpietrucha\Filament\Essentials\Record\Evaluation
 */
class Formatter implements CompatibleInterface
{
    use Bridgeable, Compatible;

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

    /**
     * @param  EvaluationComponent  $component
     * @param  MixedArray  $arguments
     */
    public static function format(Component $component, string $using, mixed $value, array $arguments): mixed
    {
        $handler = static::handler($component);

        static::bridge($handler)->eval($using, $arguments);

        return $handler->formatState($value);
    }

    /**
     * @param  EvaluationComponent  $component
     */
    public static function handler(Component $component): TextColumn|TextEntry
    {
        $name = Str::random(6);

        if ($component instanceof Column) {
            return TextColumn::make($name);
        }

        return $component->getContainer() |> TextEntry::make($name)->container(...);
    }

    /**
     * @return list<string>
     */
    public static function methods(): array
    {
        return static::$methods;
    }

    protected static function compatibility(string $method): bool
    {
        $methods = static::methods();

        return Arr::contains($methods, $method);
    }
}
