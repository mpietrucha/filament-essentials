<?php

namespace Mpietrucha\Filament\Essentials\Actions\Exports;

use Mpietrucha\Filament\Essentials\Actions\Exports\Concerns\InteractsWithActions;
use Mpietrucha\Filament\Essentials\Actions\Exports\Concerns\InteractsWithCompletedNotificationBody;

abstract class Exporter extends \Filament\Actions\Exports\Exporter
{
    use InteractsWithActions;
    use InteractsWithCompletedNotificationBody;
}
