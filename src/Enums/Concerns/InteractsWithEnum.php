<?php

namespace Mpietrucha\Filament\Essentials\Enums\Concerns;

/**
 * @phpstan-require-implements \Mpietrucha\Filament\Essentials\Enums\Contracts\InteractsWithEnumInterface
 */
trait InteractsWithEnum
{
    use \Mpietrucha\Utility\Enums\Concerns\InteractsWithEnum;

    public function getLabel(): string
    {
        return $this->label();
    }
}
