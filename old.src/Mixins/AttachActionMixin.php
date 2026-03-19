<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\AttachAction;
use Filament\Forms\Components\Select;
use Mpietrucha\Filament\Essentials\Blade;
use Mpietrucha\Filament\Essentials\Record;

/**
 * @phpstan-require-extends AttachAction
 */
trait AttachActionMixin
{
    public function avatars(?string $attribute = null): static
    {
        $this->recordSelect(function (Select $select) {
            return $select->allowHtml();
        });

        return Record::pipe(function (Record $record) use ($attribute) {
            $avatar = $record->avatar($attribute);

            $title = $this->getTable()?->getRecordTitleAttribute();

            if ($avatar === null || $title === null) {
                return null;
            }

            return Blade::renderSelectOptionWithAvatar($record->get($title), $avatar);
        }) |> $this->recordTitle(...);
    }
}
