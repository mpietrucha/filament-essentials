<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filament;

use Archilex\AdvancedTables\Filament\Indicator as ArchilexIndicator;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;
use Mpietrucha\Support\Str;

if (class_exists(ArchilexIndicator::class)) {
    class Indicator extends ArchilexIndicator
    {
        protected ?string $attribute = null;

        public function attribute(string $attribute): static
        {
            $this->attribute = $attribute;

            return $this;
        }

        public function as(string $attribute): static
        {
            return $this->attribute($attribute);
        }

        public function getTransformedKey(string $key): string
        {
            if ($this->attribute === null) {
                return $key;
            }

            $indicator = Str::dot();

            return Str::beforeLast($key, $indicator) . $indicator . $this->attribute;
        }
    }
} else {
    PackageException::missing('Indicator');
}
