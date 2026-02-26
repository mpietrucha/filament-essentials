<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports;

use Mpietrucha\Filament\Essentials\Actions\Imports\Concerns\InteractsWithAction;
use Mpietrucha\Filament\Essentials\Actions\Imports\Concerns\InteractsWithCompletedNotificationBody;

abstract class Importer extends \Filament\Actions\Imports\Importer
{
    use InteractsWithAction, InteractsWithCompletedNotificationBody;
}
