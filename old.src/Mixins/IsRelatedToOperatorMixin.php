<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Mpietrucha\Filament\Essentials\Blade;
use Mpietrucha\Filament\Essentials\Record;

/**
 * @phpstan-require-extends IsRelatedToOperator
 */
trait IsRelatedToOperatorMixin
{
    public function avatars(?string $attribute = null): static
    {
        return Record::pipe(function (Record $record) use ($attribute) {
            $avatar = $record->avatar($attribute);

            $title = $this->getTitleAttribute();

            if ($avatar === null || $title === null) {
                return $title;
            }

            return Blade::renderSelectOptionWithAvatar($record->get($title), $avatar);
        }) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
