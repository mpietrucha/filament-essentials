<?php

namespace Mpietrucha\Filament\Essentials\Listeners;

use Illuminate\Foundation\Events\LocaleUpdated;
use Mpietrucha\Filament\Essentials\Enums\Concerns\InteractsWithLocale;
use Mpietrucha\Laravel\Essentials\Locale;
use Mpietrucha\Utility\Instance;
use Mpietrucha\Utility\Type;

class ConfigureFilamentLocale
{
    public function handle(LocaleUpdated $event): void
    {
        $enum = Locale::enum();

        if (Type::null($enum)) {
            return;
        }

        if (Instance::traits($enum)->doesntContain(InteractsWithLocale::class)) {
            return;
        }

        /** @phpstan-ignore staticMethod.notFound */
        $enum::bootstrap();
    }
}
