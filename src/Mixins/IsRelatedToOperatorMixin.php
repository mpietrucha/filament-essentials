<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasSelectTitleWithAvatar;

/**
 * @phpstan-require-extends IsRelatedToOperator
 */
trait IsRelatedToOperatorMixin
{
    use HasSelectTitleWithAvatar;

    public function withAvatars(?string $attribute = null): static
    {
        return static::getSelectTitleWithAvatar(
            $attribute,
            $this->getTitleAttribute(...)
        ) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
