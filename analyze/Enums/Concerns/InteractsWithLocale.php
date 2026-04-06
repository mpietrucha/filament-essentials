<?php

declare(strict_types=1);

use Mpietrucha\Filament\Essentials\Enums\Contracts\LocaleInterface;

enum InteractsWithLocale: string implements LocaleInterface
{
    use Mpietrucha\Filament\Essentials\Enums\Concerns\InteractsWithLocale;
}
