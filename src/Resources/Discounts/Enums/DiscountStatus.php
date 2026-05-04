<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Enums;

use Filament\Support\Contracts\HasColor;
use Mpietrucha\Filament\Essentials\Enums\Concerns\InteractsWithEnum;
use Mpietrucha\Filament\Essentials\Enums\Contracts\EnumInterface;

enum DiscountStatus: string implements EnumInterface, HasColor
{
    use InteractsWithEnum;

    case Invalid = 'invalid';

    case Finished = 'finished';

    case Active = 'active';

    case Scheduled = 'scheduled';

    public static function getLabelTranslationPrefix(): string
    {
        return 'filament-essentials::discounts-plugin.status';
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Invalid => 'danger',
            self::Finished => 'gray',
            self::Active => 'success',
            self::Scheduled => 'warning'
        };
    }
}
