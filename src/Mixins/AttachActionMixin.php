<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Component;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Utility\Data;
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

        return $this->recordTitle(function (Model $record) use ($attribute) {
            $avatar = Record::avatar($record, $attribute);

            if (Type::null($avatar)) {
                return null;
            }

            $table = $this->getTable();

            if (Type::null($table)) {
                return null;
            }

            $title = Data::get($record, $table->getRecordTitleAttribute());

            return Component::renderSelectOptionWithAvatar($title, $avatar);
        });
    }
}
