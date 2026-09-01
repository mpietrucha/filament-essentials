<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filament;

use Archilex\AdvancedTables\Filament\Indicator as ArchilexIndicator;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;

if (class_exists(ArchilexIndicator::class)) {
    class Indicator extends ArchilexIndicator
    {
        protected bool $transformKey = false;

        public function transformKey(bool $transformKey = true): static
        {
            $this->transformKey = $transformKey;

            return $this;
        }

        public function getTransformedKey(string $key): string
        {
            if (! $this->transformKey) {
                return $key;
            }

            dd($key);
        }
    }
} else {
    PackageException::missing('Indicator');
}
