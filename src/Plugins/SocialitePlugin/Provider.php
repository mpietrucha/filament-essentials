<?php

namespace Mpietrucha\Filament\Essentials\Plugins\SocialitePlugin;

use Mpietrucha\Laravel\Essentials\Blade\Icon;

class Provider extends \DutchCodingCompany\FilamentSocialite\Provider
{
    public static function google(): static
    {
        $provider = static::make('google');

        Icon::fabGoogle() |> $provider->icon(...);

        return $provider;
    }
}
