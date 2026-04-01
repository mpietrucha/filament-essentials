<?php

namespace Mpietrucha\Filament\Essentials\Plugins;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Closure;
use Filament\Contracts\Plugin as FilamentPlugin;
use Filament\Panel;
use Mpietrucha\Filament\Essentials\Plugins\Concerns\HasIdentifier;
use Mpietrucha\Support\Concerns\Makeable;
use Outerweb\FilamentTranslatableFields\TranslatableFieldsPlugin;

class EssentialsPlugin implements FilamentPlugin
{
    use HasIdentifier;
    use Makeable;

    protected bool|Closure $translatable = true;

    protected bool|Closure $shield = true;

    protected bool|Closure $translations = true;

    public function withTranslatable(?Closure $translatable = null): static
    {
        $this->translatable = $translatable ?? true;

        return $this;
    }

    public function withoutTranslatable(): static
    {
        $this->translatable = false;

        return $this;
    }

    public function withShield(?Closure $shield = null): static
    {
        $this->shield = $shield ?? true;

        return $this;
    }

    public function withoutShield(): static
    {
        $this->shield = false;

        return $this;
    }

    public function withTranslations(?Closure $translations = null): static
    {
        $this->translations = $translations ?? true;

        return $this;
    }

    public function withoutTranslations(): static
    {
        $this->translations = false;

        return $this;
    }

    public function register(Panel $panel): void
    {
        if ($translatable = $this->translatable) {
            value($translatable, $plugin = TranslatableFieldsPlugin::make());

            $panel->plugin($plugin);
        }

        if ($shield = $this->shield) {
            value($shield, $plugin = FilamentShieldPlugin::make());

            $panel->plugin($plugin);
        }

        if ($translations = $this->translations) {
            value($translations, $plugin = TranslationsPlugin::make());

            $panel->plugin($plugin);
        }
    }

    public function boot(Panel $panel): void
    {
    }
}
