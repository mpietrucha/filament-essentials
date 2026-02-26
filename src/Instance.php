<?php

namespace Mpietrucha\Filament\Essentials;

use Mpietrucha\Utility\Instance\Path;
use Mpietrucha\Utility\Str;
use Mpietrucha\Utility\Type;

abstract class Instance
{
    public static function name(string $instance, ?string $group = null, ?int $level = null): string
    {
        if (Type::integer($level)) {
            $instance = Path::namespace($instance, $level);
        }

        $name = Path::name($instance);

        if (Type::null($group)) {
            return $name |> Str::lower(...);
        }

        return Str::of($name)->before($group)->lower();
    }
}
