<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Plugins;

use BezhanSalleh\PluginEssentials\Concerns\Plugin\HasLabels;
use BezhanSalleh\PluginEssentials\Concerns\Plugin\HasNavigation;
use Filament\Support\Icons\Heroicon;
use Mpietrucha\Filament\Essentials\Plugins\Concerns\RegistersResource;
use Mpietrucha\Filament\Essentials\Resources\Translations\TranslationResource;

class TranslationsPlugin extends Plugin
{
    use HasLabels;
    use HasNavigation;
    use RegistersResource;

    public function __construct()
    {
        $this->resource(TranslationResource::class);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getPluginDefaults(): array
    {
        return [
            'modelLabel' => __('filament-essentials::translations-plugin.label'),
            'pluralModelLabel' => __('filament-essentials::translations-plugin.plural_label'),
            'recordTitleAttribute' => 'key',

            'navigationIcon' => Heroicon::Language,
        ];
    }
}
