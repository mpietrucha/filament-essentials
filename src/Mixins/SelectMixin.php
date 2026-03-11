<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Mpietrucha\Filament\Essentials\Blade;
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

        return Record::pipe(function (Record $record) use ($attribute) {
            $avatar = $record->avatar($attribute);

            $title = $this->getRelationshipTitleAttribute() |> $record->get(...);

            if (Type::null($avatar)) {
                return $title;
            }

            return Blade::renderSelectOptionWithAvatar($title, $avatar);
        }) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
