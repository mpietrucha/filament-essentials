<?php

namespace Mpietrucha\Filament\Essentials\Enums\Contracts;

use Filament\Support\Contracts\HasLabel;

interface EnumInterface extends \Mpietrucha\Support\Enums\Contracts\EnumInterface, HasLabel
{
    /**
     * @return class-string<static>
     */
    public static function use(): string;
}
