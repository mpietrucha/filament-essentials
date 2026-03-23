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
    public function withAvatars(?string $attribute = null): static
    {
        $this->allowHtml();

        return Record::pipe(function (Record $record) use ($attribute): ?string {
            $avatar = $record->avatar($attribute);

            $title = $record->get(
                (string) $this->getRelationshipTitleAttribute()
            );

            if (! is_string($avatar)) {
                return null;
            }

            return Blade::renderSelectOptionWithAvatar($title, $avatar);
        }) |> $this->getOptionLabelFromRecordUsing(...);
    }
}
