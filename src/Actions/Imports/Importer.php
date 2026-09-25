<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Actions\Imports;

use Filament\Actions\Imports\Importer as FilamentImporter;
use Mpietrucha\Filament\Essentials\Actions\Imports\Concerns\HasCompletedNotificationBody;
use Mpietrucha\Filament\Essentials\Actions\Imports\Concerns\HasFluentProperties;
use Mpietrucha\Filament\Essentials\Actions\Imports\Concerns\InteractsWithActions;

abstract class Importer extends FilamentImporter
{
    use HasCompletedNotificationBody;
    use HasFluentProperties;
    use InteractsWithActions;
}
