<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Columns\ImageColumn;

/**
 * @phpstan-require-extends ImageColumn
 */
trait ImageColumnMixin
{
    public function asAvatar(?string $label = null): static
    {
        $this->circular();

        return $this->asNeighbor($label);
    }
}
