<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Plugins;

use Filament\Panel;
use Mpietrucha\Filament\Essentials\GlobalSearch\Providers\MeilisearchGlobalSearchProvider;
use Mpietrucha\Filament\Essentials\GlobalSearch\Providers\ScoutGlobalSearchProvider;

class ScoutPlugin extends Plugin
{
    public function register(Panel $panel): void
    {
        $driver = config('scout.driver');

        if (! is_string($driver)) {
            return;
        }

        match ($driver) {
            'meilisearch' => MeilisearchGlobalSearchProvider::class,
            default => ScoutGlobalSearchProvider::class
        } |> $panel->globalSearch(...);
    }
}
