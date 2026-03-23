<?php

namespace Mpietrucha\Filament\Essentials\Locale;

class IntlDatePatternGenerator extends \IntlDatePatternGenerator
{
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
