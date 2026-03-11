<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Mpietrucha\Filament\Essentials\Blade;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-require-extends \Filament\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator
 */
trait IsRelatedToOperatorMixin
{
    public function avatars(?string $attribute = null): static
    {
        return Record::pipe(function (Record $record) use ($attribute) {
            $avatar = $record->avatar($attribute);

            $title = $this->getTitleAttribute() |> $record->get(...);

            if (Type::null($avatar)) {
                return $title;
            }

            return Blade::renderSelectOptionWithAvatar($title, $avatar);
        }) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
