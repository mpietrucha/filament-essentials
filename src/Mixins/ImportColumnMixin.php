<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\Imports\ImportColumn;

/**
 * @phpstan-require-extends ImportColumn
 */
trait ImportColumnMixin
{
    public function labelAndExampleHeader(string $labelAndExampleHeader): static
    {
        $this->label($labelAndExampleHeader);
        $this->exampleHeader($labelAndExampleHeader);

        return $this;
    }
}
