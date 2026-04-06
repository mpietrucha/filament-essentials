<?php

declare(strict_types=1);

use Mpietrucha\Filament\Essentials\Enums\Contracts\EnumInterface;

enum InteractsWithEnum implements EnumInterface
{
    use Mpietrucha\Filament\Essentials\Enums\Concerns\InteractsWithEnum;
}
