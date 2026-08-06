<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Infolists\Components\TextEntry;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\InteractsWithPrice;

/**
 * @phpstan-require-extends TextEntry
 */
trait TextEntryMixin
{
    use InteractsWithPrice;
}
