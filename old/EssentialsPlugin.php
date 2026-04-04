<?php

namespace Mpietrucha\Filament\Essentials\Plugins;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Closure;
use Filament\Contracts\Plugin as FilamentPlugin;
use Filament\Panel;
use Mpietrucha\Filament\Essentials\Plugins\Concerns\HasIdentifier;
use Mpietrucha\Filament\Essentials\Plugins\Concerns\HasPlugins;
use Mpietrucha\Support\Concerns\Makeable;
use Outerweb\FilamentTranslatableFields\TranslatableFieldsPlugin;

class EssentialsPlugin implements FilamentPlugin
{
    use HasIdentifier;
    use HasPlugins;
    use Makeable;

    protected bool|Closure $translatable = true;

    protected bool|Closure $shield = true;

    protected bool|Closure $translations = true;

    protected bool|Closure $scout = true;

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

    public function withScout(?Closure $scout = null): static
    {
        $this->scout = $scout ?? true;

        return $this;
    }

    public function withoutScout(): static
    {
        $this->scout = false;

        return $this;
    }

    public function register(Panel $panel): void
    {
        static::plugin($panel, TranslatableFieldsPlugin::make(...), $this->translatable);

        static::plugin($panel, FilamentShieldPlugin::make(...), $this->shield);

        static::plugin($panel, TranslationsPlugin::make(...), $this->translations);

        static::plugin($panel, ScoutPlugin::make(...), $this->scout);
    }

    public function boot(Panel $panel): void
    {
    }
}
