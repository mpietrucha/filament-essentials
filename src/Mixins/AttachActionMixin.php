<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\AttachAction;
use Filament\Forms\Components\Select;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasSelectTitleWithAvatar;

/**
 * @phpstan-require-extends AttachAction
 */
trait AttachActionMixin
{
    use HasSelectTitleWithAvatar;

    public function withAvatars(?string $attribute = null): static
    {
        $this->recordSelect(static function (Select $select): Select {
            return $select->allowHtml();
        });

        return static::getSelectTitleWithAvatar(
            $attribute,
            fn (): ?string => $this->getTable()?->getRecordTitleAttribute(),
        ) |> $this->recordTitle(...);
    }
}
