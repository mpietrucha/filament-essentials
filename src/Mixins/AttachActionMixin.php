<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\Select;
use Mpietrucha\Filament\Essentials\Blade;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-require-extends \Filament\Actions\AttachAction
 */
trait AttachActionMixin
{
    public function avatars(?string $attribute = null): static
    {
        $this->recordSelect(function (Select $select) {
            return $select->allowHtml();
        });

        return Record::bind(function (Record $record) use ($attribute) {
            $avatar = $record->avatar($attribute);

            if (Type::null($avatar)) {
                return null;
            }

            $table = $this->getTable();

            if (Type::null($table)) {
                return null;
            }

            $title = $table->getRecordTitleAttribute() |> $record->get(...);

            return Blade::renderSelectOptionWithAvatar($title, $avatar);
        }) |> $this->recordTitle(...);
    }
}
