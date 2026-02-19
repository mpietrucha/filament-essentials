<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Component;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Utility\Data;
use Mpietrucha\Utility\Type;

trait IsRelatedToOperatorMixin
{
    public function avatars(?string $attribute = null): static
    {
        return $this->getOptionLabelFromRecordUsing(function (Select $component, Model $record) use ($attribute) {
            $component->allowHtml();

            $title = Data::get($record, $this->getTitleAttribute());

            $avatar = Record::avatar($record, $attribute);

            if (Type::null($avatar)) {
                return $title;
            }

            return Component::renderSelectOptionWithAvatar($title, $avatar);
        });
    }
}
