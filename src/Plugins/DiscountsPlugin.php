<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Plugins;

use BezhanSalleh\PluginEssentials\Concerns\Plugin\HasLabels;
use BezhanSalleh\PluginEssentials\Concerns\Plugin\HasNavigation;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Mpietrucha\Filament\Essentials\Plugins\Concerns\HasResource;
use Mpietrucha\Filament\Essentials\Resources\Discounts\DiscountResource;

/**
 * @phpstan-type FilamentResource class-string<Resource>
 */
class DiscountsPlugin extends Plugin
{
    use HasLabels;
    use HasNavigation;
    use HasResource;

    /**
     * @var FilamentResource
     */
    protected string $defaultResource = DiscountResource::class;

    protected bool $shouldRegisterResource = false;

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
