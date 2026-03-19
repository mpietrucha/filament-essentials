<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Columns\ImageColumn;
use Mpietrucha\Support\Backtrace;
use Mpietrucha\Support\Backtrace\Frame;
use Mpietrucha\Support\Str;

/**
 * @phpstan-require-extends ImageColumn
 */
trait ImageColumnMixin
{
    public function avatar(?string $label = null): static
    {
        $this->label(function () use ($label) {
            if ($label === null) {
                return Str::none();
            }

            $visible = Backtrace::get(DEBUG_BACKTRACE_IGNORE_ARGS, 10)->first(function (Frame $frame) {
                $function = $frame->getFunction();

                return Str::contains($function, 'mapTableColumnToArray');
            });

            if ($visible) {
                return $label;
            }

            return Str::none();
        });

        return $this->circular()->width('1%');
    }
}
