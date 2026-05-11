<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\AttachAction;
use Filament\Forms\Components\Select;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasAvatarBuilderClosure;

/**
 * @phpstan-require-extends AttachAction
 */
trait AttachActionMixin
{
    use HasAvatarBuilderClosure;

    public function withAvatars(?string $attribute = null): static
    {
        $this->recordSelect(static function (Select $select): Select {
            return $select->allowHtml();
        });

        return static::getAvatarBuilderClosure(
            $attribute,
            fn (): ?string => $this->getTable()?->getRecordTitleAttribute(),
        ) |> $this->recordTitle(...);
    }
}
