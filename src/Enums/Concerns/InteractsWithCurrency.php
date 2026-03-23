<?php

namespace Mpietrucha\Filament\Essentials\Enums\Concerns;

use Mpietrucha\Filament\Essentials\Enums\Contracts\CurrencyInterface;

/**
 * @phpstan-require-implements CurrencyInterface
 */
trait InteractsWithCurrency
{
    use InteractsWithEnum;
    use \Mpietrucha\Laravel\Essentials\Enums\Concerns\InteractsWithCurrency;
}
