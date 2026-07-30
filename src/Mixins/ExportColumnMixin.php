<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\Exports\ExportColumn;

/**
 * @phpstan-require-extends ExportColumn
 */
trait ExportColumnMixin
{
    public function boolean(?string $trueLabel = null, ?string $falseLabel = null): static
    {
        $trueLabel ??= __('filament-forms::components.radio.boolean.true');
        $falseLabel ??= __('filament-forms::components.radio.boolean.false');

        $this->formatStateUsing(static fn (mixed $state): string => match ((bool) $state) {
            true => $trueLabel,
            false => $falseLabel,
        });

        return $this;
    }
}
