<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\AttachAction;
use Filament\Forms\Components\Select;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasAvatarConfigurator;

/**
 * @phpstan-require-extends AttachAction
 */
trait AttachActionMixin
{
    use HasAvatarConfigurator;

    public function withAvatars(?string $attribute = null): static
    {
        $this->recordSelect(static function (Select $select): Select {
            return $select->allowHtml();
        });

        return $this->getAvatarConfigurator(
            $attribute,
            fn () => $this->getTable()?->getRecordTitleAttribute(),
        ) |> $this->recordTitle(...);
    }
}
