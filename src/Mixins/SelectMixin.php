<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\View;
use Mpietrucha\Utility\Data;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-require-extends \Filament\Forms\Components\Select
 */
trait SelectMixin
{
    public function avatars(string $attribute = 'avatar'): static
    {
        $this->allowHtml();

        return $this->getOptionLabelFromRecordUsing(function (Model $record) use ($attribute) {
            $title = Data::get($record, $this->getRelationshipTitleAttribute());

            $avatar = Data::get($record, $attribute);

            if (Type::null($avatar)) {
                return $title;
            }

            return View::selectTitleWithAvatar($title, $avatar);
        });
    }
}
