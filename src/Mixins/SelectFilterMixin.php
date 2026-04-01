<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Filters\SelectFilter;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasAvatarConfigurator;

/**
 * @phpstan-require-extends SelectFilter
 */
trait SelectFilterMixin
{
    use HasAvatarConfigurator;

    public function withAvatars(?string $attribute = null): static
    {
        return static::getAvatarConfigurator(
            $attribute,
            $this->getRelationshipTitleAttribute(...)
        ) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
