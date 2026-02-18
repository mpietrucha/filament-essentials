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
        return Str::none() |> $this->width('1%')->circular()->label(...);
    }
}
