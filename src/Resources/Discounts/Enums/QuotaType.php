<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Enums;

use Mpietrucha\Filament\Essentials\Enums\Concerns\InteractsWithEnum;
use Mpietrucha\Filament\Essentials\Enums\Contracts\EnumInterface;

enum QuotaType: string implements EnumInterface
{
    use InteractsWithEnum;

    case None = 'none';

    case Existing = 'existing';

    case New = 'new';

    public static function getLabelTranslationPrefix(): string
    {
        return 'filament-essentials::discounts-plugin.form.quota';
    }
}
