<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasAvatarBuilderClosure;

/**
 * @phpstan-require-extends IsRelatedToOperator
 */
trait IsRelatedToOperatorMixin
{
    use HasAvatarBuilderClosure;

    public function withAvatars(?string $attribute = null): static
    {
        return static::getAvatarBuilderClosure(
            $attribute,
            $this->getTitleAttribute(...)
        ) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
