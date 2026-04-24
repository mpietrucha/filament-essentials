<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Plugins;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Closure;
use Filament\Panel;
use Outerweb\FilamentTranslatableFields\TranslatableFieldsPlugin;

class EssentialsPlugin extends Plugin
{
    protected bool|Closure $scout = true;

    protected bool|Closure $shield = true;

    protected bool|Closure $discounts = false;

    protected bool|Closure $translations = true;

    protected bool|Closure $translatable = true;

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

    public function withDiscounts(?Closure $discounts = null): static
    {
        $this->discounts = $discounts ?? true;

        return $this;
    }

    public function withoutDiscounts(): static
    {
        $this->discounts = false;

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

    public function register(Panel $panel): void
    {
        static::install(ScoutPlugin::make(...), $panel, $this->scout);

        static::install(FilamentShieldPlugin::make(...), $panel, $this->shield);

        static::install(DiscountsPlugin::make(...), $panel, $this->discounts);

        static::install(TranslationsPlugin::make(...), $panel, $this->translations);

        static::install(TranslatableFieldsPlugin::make(...), $panel, $this->translatable);
    }
}
