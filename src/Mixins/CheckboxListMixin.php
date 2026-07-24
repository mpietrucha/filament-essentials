<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\CheckboxList;

/**
 * @phpstan-require-extends CheckboxList
 */
trait CheckboxListMixin
{
    public function hideDisabled(): static
    {
        return $this->extraAttributes(['class' => 'fi-fo-checkbox-list-options-disabled-hidden']);
    }
}
