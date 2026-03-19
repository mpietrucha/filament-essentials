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
        $this->recordSelect(static function (Select $select): Select {
            return $select->allowHtml();
        });

        return Record::pipe(function (Record $record) use ($attribute): ?string {
            $avatar = $record->avatar($attribute);

            $title = $record->get(
                (string) $this->getTable()?->getRecordTitleAttribute()
            );

            if (! is_string($avatar) || ! is_string($title)) {
                return null;
            }

            return Blade::renderSelectOptionWithAvatar($title, $avatar);
        }) |> $this->recordTitle(...);
    }
}
