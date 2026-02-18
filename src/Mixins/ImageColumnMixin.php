<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Mpietrucha\Utility\Str;

/**
 * @phpstan-require-extends \Filament\Tables\Columns\ImageColumn
 */
trait ImageColumnMixin
{
    public function avatar(): static
    {
        $this->width('1%');

        $this->circular();

        return Str::none() |> $this->label(...);
    }
}
