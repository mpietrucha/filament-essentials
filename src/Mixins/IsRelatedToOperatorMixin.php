<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Mpietrucha\Filament\Essentials\Component;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-require-extends \Filament\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator
 */
trait IsRelatedToOperatorMixin
{
    public function avatars(?string $attribute = null): static
    {
        return Record::use(function (Record $record) use ($attribute) {
            $avatar = $record->avatar($attribute);

            $title = $this->getTitleAttribute() |> $record->get(...);

            if (Type::null($avatar)) {
                return $title;
            }

            return Component::renderSelectOptionWithAvatar($title, $avatar);
        }) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
