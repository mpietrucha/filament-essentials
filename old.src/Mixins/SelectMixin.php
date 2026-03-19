<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\Select;
use Mpietrucha\Filament\Essentials\Blade;
use Mpietrucha\Filament\Essentials\Record;

/**
 * @phpstan-require-extends Select
 */
trait SelectMixin
{
    public function avatars(?string $attribute = null): static
    {
        $this->allowHtml();

        return Record::pipe(function (Record $record) use ($attribute) {
            $avatar = $record->avatar($attribute);

            $title = $this->getRelationshipTitleAttribute();

            if ($avatar === null || $title === null) {
                return null;
            }

            return Blade::renderSelectOptionWithAvatar($record->get($title), $avatar);
        }) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
