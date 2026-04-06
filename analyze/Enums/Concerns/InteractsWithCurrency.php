<?php

declare(strict_types=1);

use Mpietrucha\Filament\Essentials\Enums\Contracts\CurrencyInterface;

enum InteractsWithCurrency: string implements CurrencyInterface
{
    use Mpietrucha\Filament\Essentials\Enums\Concerns\InteractsWithCurrency;
}
