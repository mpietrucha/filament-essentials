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

        return str(
            Path::name($class)
        )->before($suffix ?? Str::none())->lower();
    }
}
