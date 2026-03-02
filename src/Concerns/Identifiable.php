<?php

namespace Mpietrucha\Filament\Essentials\Concerns;

trait Identifiable
{
    public static function identify(?string $suffix = null, ?int $level = null): string
    {
        $instance = static::class;

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
