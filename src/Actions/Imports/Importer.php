<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports;

use Mpietrucha\Filament\Essentials\Actions\Imports\Concerns\HasCompletedNotificationBody;

abstract class Importer extends \Filament\Actions\Imports\Importer
{
    use HasCompletedNotificationBody;
}
