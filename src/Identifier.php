<?php

namespace Mpietrucha\Filament\Essentials;

use Mpietrucha\Utility\Backtrace;
use Mpietrucha\Utility\Instance\Path;
use Mpietrucha\Utility\Str;
use Mpietrucha\Utility\Type;

abstract class Identifier
{
    public static function identify(?string $suffix = null, ?int $level = null): string
    {
        /** @var null|string $instance */
        $instance = Backtrace::get(DEBUG_BACKTRACE_IGNORE_ARGS, 1)
            ->map
            ->instance();

        if (Type::null($instance)) {
            return Str::none();
        }

        return static::get($instance, $suffix, $level);
    }

    public static function get(string $instance, ?string $suffix = null, ?int $level = null): string
    {
        if (Type::integer($level)) {
            $instance = Path::namespace($instance, $level);
        }

        $name = Path::name($instance);

        if (Type::null($suffix)) {
            return $name |> Str::lower(...);
        }

        return Str::of($name)->before($suffix)->lower();
    }
}
