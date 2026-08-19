<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Locale\Enums\Concerns;

use Mpietrucha\Filament\Essentials\Enums\Concerns\InteractsWithEnum;
use Mpietrucha\Filament\Essentials\Locale\Enums\Contracts\CurrencyInterface;

/**
 * @phpstan-require-implements CurrencyInterface
 */
trait InteractsWithCurrency
{
    use InteractsWithEnum;
    use \Mpietrucha\Laravel\Essentials\Locale\Enums\Concerns\InteractsWithCurrency;
}
