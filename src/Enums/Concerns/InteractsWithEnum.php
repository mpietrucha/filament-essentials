<?php

namespace Mpietrucha\Filament\Essentials\Enums\Concerns;

use Mpietrucha\Filament\Essentials\Enums\Contracts\EnumInterface;

/**
 * @phpstan-require-implements EnumInterface
 */
trait InteractsWithEnum
{
    use \Mpietrucha\Support\Enums\Concerns\InteractsWithEnum;

    /**
     * @return class-string<static>
     */
    public static function use(): string
    {
        return static::class;
    }

    public function getLabel(): string
    {
        return '';
    }
}
