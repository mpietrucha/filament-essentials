<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Locale\Filters;

use Filament\Tables\Filters\SelectFilter;
use Mpietrucha\Filament\Essentials\Locale\Filters\Concerns\InteractsWithCurrencyFilter;

class CurrencyFilter extends SelectFilter
{
    use InteractsWithCurrencyFilter;
}
