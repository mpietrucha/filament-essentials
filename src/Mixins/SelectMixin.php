<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Closure;

/**
 * @phpstan-require-extends \Filament\Forms\Components\Select
 */
trait SelectMixin
{
    public function avatar(Closure|string $avatar): static
    {
        $this->allowHtml();

        return $this->getOptionLabelFromRecordUsing(function (Model $record) use ($avatar) {
            $title = Data::get($record, $this->getRelationshipTitleAttribute());

            $avatar = $this->evaluate($avatar);

            return View::selectTitleWithAvatar($title, $avatar);
        });
    }
}
