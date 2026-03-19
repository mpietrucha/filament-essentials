<?php

namespace Mpietrucha\Filament\Essentials\Concerns;

use Mpietrucha\Support\Instance\Path;
use Mpietrucha\Support\Str;

/**
 * @internal
 */
trait Identifiable
{
    public static function identify(?string $suffix = null, ?int $level = null): string
    {
        $class = static::class;

        if (is_int($level)) {
            $class = Path::namespace($class, $level);
        }

        $name = Path::name($class);

        if ($suffix === null) {
            return Str::lower($name);
        }

        return Str::of($name)->before($suffix)->lower();
    }
}
