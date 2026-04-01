<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasAvatarConfigurator;

/**
 * @phpstan-require-extends IsRelatedToOperator
 */
trait IsRelatedToOperatorMixin
{
    use HasAvatarConfigurator;

    public function withAvatars(?string $attribute = null): static
    {
        return $this->getAvatarConfigurator(
            $attribute,
            $this->getTitleAttribute(...)
        ) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
