<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Mpietrucha\Filament\Essentials\Component;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-require-extends \Filament\Forms\Components\Select
 */
trait SelectMixin
{
    public function avatars(?string $attribute = null): static
    {
        $this->allowHtml();

        return Record::use(function (Record $record) use ($attribute) {
            $avatar = $record->avatar($attribute);

            $title = $this->getRelationshipTitleAttribute() |> $record->get(...);

            if (Type::null($avatar)) {
                return $title;
            }

            return Component::renderSelectOptionWithAvatar($title, $avatar);
        }) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
