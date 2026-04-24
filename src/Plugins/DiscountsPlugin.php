<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Plugins;

use BezhanSalleh\PluginEssentials\Concerns\Plugin\HasLabels;
use BezhanSalleh\PluginEssentials\Concerns\Plugin\HasNavigation;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Mpietrucha\Filament\Essentials\Resources\Discounts\DiscountResource;

class DiscountsPlugin extends Plugin
{
    use HasLabels;
    use HasNavigation;

    public function register(Panel $panel): void
    {
        $panel->resources([
            DiscountResource::class,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getPluginDefaults(): array
    {
        return [
            'modelLabel' => __('filament-essentials::discounts-plugin.label'),
            'pluralModelLabel' => __('filament-essentials::discounts-plugin.plural_label'),
            'recordTitleAttribute' => 'id',

            'navigationIcon' => Heroicon::Tag,
        ];
    }
}
