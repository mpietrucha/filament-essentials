<?php

namespace Mpietrucha\Filament\Essentials\Enums\Concerns;

use Mpietrucha\Filament\Essentials\Enums\Contracts\EnumInterface;

/**
 * @phpstan-require-implements EnumInterface
 */
trait InteractsWithEnum
{
    use \Mpietrucha\Support\Enums\Concerns\InteractsWithEnum;

    public function getLabel(): string
    {
        return '';
    }
}
