<?php

namespace Mpietrucha\Filament\Essentials;

use Mpietrucha\Filament\Essentials\Commands\GenerateColors;
use Mpietrucha\Filament\Essentials\Commands\GeneratePolicies;
use Mpietrucha\Laravel\Essentials\Package\Builder;
use Mpietrucha\Laravel\Essentials\Package\ServiceProvider;

class EssentialsServiceProvider extends ServiceProvider
{
    public function configure(Builder $package): void
    {
        $package->name('filament-essentials');

        $package->hasConsoleCommands([
            GenerateColors::class,
            GeneratePolicies::class,
        ]);
    }
}
