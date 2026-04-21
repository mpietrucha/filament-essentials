<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Exception;

use Mpietrucha\Support\Exception\RuntimeException;

class PackageException extends RuntimeException
{
    public static function missing(string $name): never
    {
        static::throw('%s requires archilex/filament-filter-sets to be installed', $name);
    }
}
