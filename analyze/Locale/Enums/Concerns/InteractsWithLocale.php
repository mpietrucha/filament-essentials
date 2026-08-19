<?php

declare(strict_types=1);

use Mpietrucha\Filament\Essentials\Locale\Enums\Contracts\LocaleInterface;

enum InteractsWithLocale: string implements LocaleInterface
{
    use Mpietrucha\Filament\Essentials\Locale\Enums\Concerns\InteractsWithLocale;
}
