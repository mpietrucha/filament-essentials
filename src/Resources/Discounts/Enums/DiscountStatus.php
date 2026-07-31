<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Enums;

use Filament\Support\Contracts\HasColor;
use Mpietrucha\Filament\Essentials\Colors\ColorVariant;
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
        $color = match ($this) {
            self::Invalid => ColorVariant::danger(),
            self::Finished => ColorVariant::gray(),
            self::Active => ColorVariant::success(),
            self::Scheduled => ColorVariant::warning(),
        };

        return $color->name();
    }
}
