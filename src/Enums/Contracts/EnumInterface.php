<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Enums\Contracts;

use Filament\Support\Contracts\HasLabel;
use Mpietrucha\Support\Enums\Contracts\EnumInterface as SupportEnumInterface;

interface EnumInterface extends HasLabel, SupportEnumInterface
{
    /**
     * @return class-string<static>
     */
    public static function options(): string;
}
