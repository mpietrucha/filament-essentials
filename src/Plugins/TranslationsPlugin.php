<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Plugins;

use BezhanSalleh\PluginEssentials\Concerns\Plugin\HasLabels;
use BezhanSalleh\PluginEssentials\Concerns\Plugin\HasNavigation;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Mpietrucha\Filament\Essentials\Resources\Translations\TranslationResource;

class TranslationsPlugin extends Plugin
{
    use HasLabels;
    use HasNavigation;

    public function register(Panel $panel): void
    {
        $panel->resources([
            TranslationResource::class,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getPluginDefaults(): array
    {
        return [
            'modelLabel' => __('filament-essentials::translation.label'),
            'pluralModelLabel' => __('filament-essentials::translation.plural_label'),
            'recordTitleAttribute' => 'key',

            'navigationIcon' => Heroicon::Language,
        ];
    }
}
