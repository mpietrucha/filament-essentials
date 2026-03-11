<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Mpietrucha\Utility\Backtrace;
use Mpietrucha\Utility\Str;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-require-extends \Filament\Tables\Columns\ImageColumn
 */
trait ImageColumnMixin
{
    public function avatar(?string $label = null): static
    {
        $this->label(function () use ($label) {
            if (Type::null($label)) {
                return Str::none();
            }

            $visible = Backtrace::get(DEBUG_BACKTRACE_IGNORE_ARGS, 10)
                ->map
                ->function()
                ->contains('mapTableColumnToArray');

            if ($visible) {
                return $label;
            }

            return Str::none();
        });

        return $this->circular()->width('1%');
    }
}
