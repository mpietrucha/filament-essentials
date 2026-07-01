<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\TextInput;

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
}
