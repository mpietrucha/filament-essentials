<?php

namespace Mpietrucha\Filament\Essentials\Concerns;

use Mpietrucha\Support\ClassNamespace;
use Mpietrucha\Support\Str;

/**
 * @internal
 */
trait Identifiable
{
    public static function identify(?string $suffix = null, ?int $level = null): string
    {
        $class = get_called_class();

        if (is_int($level)) {
            $class = ClassNamespace::parent($class, $level);
        }

        return str(
            ClassNamespace::name($class)
        )->before($suffix ?? Str::none())->lower()->toString();
    }
}
