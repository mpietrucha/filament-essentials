<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\Select;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasAvatarBuilderClosure;

/**
 * @phpstan-require-extends Select
 */
trait SelectMixin
{
    use HasAvatarBuilderClosure;

    public function withAvatars(?string $attribute = null): static
    {
        $this->allowHtml();

        return static::getAvatarBuilderClosure(
            $attribute,
            $this->getRelationshipTitleAttribute(...)
        ) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
