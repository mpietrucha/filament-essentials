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
        return Record::pipe(function (Record $record) use ($attribute): string {
            $avatar = $record->avatar($attribute);

            $title = $record->get(
                (string) $this->getTitleAttribute()
            );

            if (! is_string($avatar)) {
                return $title;
            }

            return Blade::renderSelectOptionWithAvatar($title, $avatar);
        }) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
