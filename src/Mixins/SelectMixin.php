<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\Select;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasSelectTitleWithAvatar;

/**
 * @phpstan-require-extends Select
 */
trait SelectMixin
{
    use HasSelectTitleWithAvatar;

    public function withAvatars(?string $attribute = null): static
    {
        $this->allowHtml();

        return static::getSelectTitleWithAvatar(
            $attribute,
            $this->getRelationshipTitleAttribute(...)
        ) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
