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
        $this->extraAttributes(['x-paste-spreadsheet' => true]);

        return $this;
    }

    public function decimal(int $fractionDigits = 2): static
    {
        $this->isNumeric = true;
        $this->inputMode('decimal');

        sprintf('0.%s', Str::padLeft('1', $fractionDigits, '0')) |> $this->step(...);

        sprintf('decimal:%s', $fractionDigits) |> $this->rule(...);

        $this->extraInputAttributes(['x-decimal' => $fractionDigits]);

        return $this;
    }
}
