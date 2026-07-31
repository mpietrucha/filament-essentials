<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Infolists\Components\TextEntry;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasPrice;

/**
 * @phpstan-require-extends TextEntry
 */
trait TextEntryMixin
{
    use HasPrice;
}
