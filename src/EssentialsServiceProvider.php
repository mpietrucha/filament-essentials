<?php

namespace Mpietrucha\Filament\Essentials;

use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Mpietrucha\Filament\Essentials\Commands\GenerateColors;
use Mpietrucha\Filament\Essentials\Commands\GeneratePolicies;
use Mpietrucha\Filament\Essentials\Mixins\ImageColumnMixin;
use Mpietrucha\Filament\Essentials\Mixins\SelectMixin;
use Mpietrucha\Filament\Essentials\Mixins\TextColumnMixin;
use Mpietrucha\Laravel\Essentials\Mixin;
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

    public function bootingPackage(): void
    {
        Mixin::use(Select::class, SelectMixin::class);

        Mixin::use(TextColumn::class, TextColumnMixin::class);

        Mixin::use(ImageColumn::class, ImageColumnMixin::class);
    }
}
