<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Columns\ImageColumn;

/**
 * @phpstan-require-extends ImageColumn
 *
 * @mixin TextColumnMixin
 */
trait ImageColumnMixin
{
    public function asAvatar(?string $label = null): static
    {
        $this->circular();

        $this->asNeighbor($label);

        return $this;
    }
}
