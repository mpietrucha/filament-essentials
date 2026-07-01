<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Infolists\Components\TextEntry;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasPriceWithDiscount;

/**
 * @phpstan-require-extends TextEntry
 */
trait TextEntryMixin
{
    use HasPriceWithDiscount;
}
