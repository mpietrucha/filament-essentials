<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Pages;

use Filament\Resources\Pages\ManageRecords;
use Mpietrucha\Filament\Essentials\Plugins\DiscountsPlugin;

class ManageDiscounts extends ManageRecords
{
    #[\Override]
    public static function getResource(): string
    {
        return DiscountsPlugin::get()->getResource();
    }
}
