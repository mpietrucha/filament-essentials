<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\Select;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasAvatarConfigurator;

/**
 * @phpstan-require-extends Select
 */
trait SelectMixin
{
    use HasAvatarConfigurator;

    public function withAvatars(?string $attribute = null): static
    {
        $this->allowHtml();

        return static::getAvatarConfigurator(
            $attribute,
            $this->getRelationshipTitleAttribute(...)
        ) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
