<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Actions\Imports;

use Mpietrucha\Filament\Essentials\Actions\Imports\Concerns\InteractsWithActions;
use Mpietrucha\Filament\Essentials\Actions\Imports\Concerns\InteractsWithCompletedNotificationBody;

abstract class Importer extends \Filament\Actions\Imports\Importer
{
    use InteractsWithActions;
    use InteractsWithCompletedNotificationBody;
}
