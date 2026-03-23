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
    public function withAvatar(?string $label = null): static
    {
        $this->label(static function () use ($label): string {
            if ($label === null) {
                return Str::none();
            }

            $visible = Backtrace::get(DEBUG_BACKTRACE_IGNORE_ARGS, 5)->first(static function (Frame $frame): bool {
                return $frame->getFunction() === 'mapTableColumnToArray';
            });

            if ($visible) {
                return $label;
            }

            return Str::none();
        });

        return $this->circular()->width('1%');
    }
}
