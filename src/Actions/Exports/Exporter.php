<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Actions\Exports;

use Filament\Actions\Exports\Exporter as FilamentExporter;
use Mpietrucha\Filament\Essentials\Actions\Exports\Concerns\InteractsWithActions;
use Mpietrucha\Filament\Essentials\Actions\Exports\Concerns\InteractsWithCompletedNotificationBody;

abstract class Exporter extends FilamentExporter
{
    use InteractsWithActions;
    use InteractsWithCompletedNotificationBody;
}
