<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Actions\Imports;

use Filament\Actions\Imports\Importer as FilamentImporter;
use Mpietrucha\Filament\Essentials\Actions\Imports\Concerns\InteractsWithActions;
use Mpietrucha\Filament\Essentials\Actions\Imports\Concerns\InteractsWithCompletedNotificationBody;

abstract class Importer extends FilamentImporter
{
    use InteractsWithActions;
    use InteractsWithCompletedNotificationBody;
}
