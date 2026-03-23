<?php

namespace Mpietrucha\Filament\Essentials\Locale;

use Mpietrucha\Support\Concerns\Makeable;

class IntlDatePatternGenerator extends \IntlDatePatternGenerator
{
    use Makeable;

    public function toPHPDateFormat(string $skeleton): ?string
    {
        $pattern = $this->getBestPattern($skeleton);

        if ($pattern === false) {
            return null;
        }

        return strtr($pattern, [
            'dd' => 'd', 'd' => 'j',
            'MM' => 'm', 'M' => 'n',
            'yyyy' => 'Y', 'yy' => 'y', 'y' => 'Y',
            'HH' => 'H', 'H' => 'G',
            'hh' => 'h', 'h' => 'g',
            'mm' => 'i',
            'ss' => 's',
            'a' => 'A',
        ]);
    }
}
