<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Columns\ImageColumn;
use Mpietrucha\Support\Backtrace;
use Mpietrucha\Support\Str;

/**
 * @phpstan-require-extends ImageColumn
 */
trait ImageColumnMixin
{
    public function asAvatar(?string $label = null): static
    {
        $this->label(static function () use ($label): string {
            if ($label === null) {
                return Str::none();
            }

            return Backtrace::get(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
                ->map
                ->getFunction()
                ->contains('mapTableColumnToArray') ? Str::none() : $label;
        });

        return $this->circular()->width('1%');
    }
}
