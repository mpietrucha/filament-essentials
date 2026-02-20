<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
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
        return $this->getOptionLabelFromRecordUsing(function (Select $component, Model $record) use ($attribute) {
            $context = Record::context($record);

            $avatar = $context->avatar($attribute);

            $title = $this->getTitleAttribute() |> $context->get(...);

            if (Type::null($avatar)) {
                return $title;
            }

            return Component::renderSelectOptionWithAvatar($title, $avatar);
        });
    }
}
