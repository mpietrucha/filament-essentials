<?php

namespace Mpietrucha\Filament\Essentials\Plugins;

use BezhanSalleh\PluginEssentials\Concerns\Plugin\HasLabels;
use BezhanSalleh\PluginEssentials\Concerns\Plugin\HasNavigation;
use Filament\Contracts\Plugin as FilamentPlugin;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Mpietrucha\Filament\Essentials\Resources\Translations\TranslationResource;
use Mpietrucha\Support\Concerns\Makeable;

class FilamentTranslationsPlugin implements FilamentPlugin
{
    use HasLabels;
    use HasNavigation;
    use Makeable;

    public function getId(): string
    {
        return 'mpietrucha-filament-translations-plugin';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            TranslationResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
    }

    /**
     * @return array<string, mixed>
     */
    protected function getPluginDefaults(): array
    {
        return [
            'modelLabel' => __('filament-essentials::translation.label'),
            'pluralModelLabel' => __('filament-essentials::translation.plural_label'),

            'navigationIcon' => Heroicon::Language,
        ];
    }
}
