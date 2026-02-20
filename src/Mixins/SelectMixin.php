<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Illuminate\Database\Eloquent\Model;
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

        return $this->getOptionLabelFromRecordUsing(function (Model $record) use ($attribute) {
            $context = Record::context($record);

            $avatar = $context->avatar($attribute);

            $title = $this->getRelationshipTitleAttribute() |> $context->get(...);

            if (Type::null($avatar)) {
                return $title;
            }

            return Component::renderSelectOptionWithAvatar($title, $avatar);
        });
    }
}
