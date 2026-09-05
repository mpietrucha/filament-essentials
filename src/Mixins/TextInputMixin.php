<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

/**
 * @phpstan-require-extends TextInput
 */
trait TextInputMixin
{
    public function pasteSpreadsheet(): static
    {
        $this->extraFieldWrapperAttributes([
            'x-paste-spreadsheet' => true,
        ]);

        return $this;
    }

    public function finishPasteSpreadsheet(): static
    {
        $this->extraFieldWrapperAttributes([
            'x-paste-spreadsheet-finish' => true,
        ]);

        return $this;
    }

    public function decimal(int $decimalPlaces = 2): static
    {
        $this->numeric();

        $this->extraInputAttributes(['x-decimal' => $decimalPlaces]);

        sprintf('0.%s', Str::padLeft('1', $decimalPlaces, '0')) |> $this->step(...);

        return $this;
    }
}
