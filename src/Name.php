<?php

namespace Mpietrucha\Filament\Essentials;

use Mpietrucha\Utility\Instance\Path;
use Mpietrucha\Utility\Str;
use Mpietrucha\Utility\Type;

abstract class Name
{
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
