<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filament;

use Archilex\AdvancedTables\Filament\Indicator as ArchilexIndicator;
use Illuminate\Support\Str;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;

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
            if (null === $attribute = $this->attribute) {
                return $key;
            }

            $indicator = Str::dot();

            return Str::beforeLast($key, $indicator) . $indicator . $attribute;
        }
    }
} else {
    PackageException::missing('Indicator');
}
