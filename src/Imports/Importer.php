<?php

namespace Mpietrucha\Filament\Essentials\Imports;

use Mpietrucha\Filament\Essentials\Imports\Concerns\HasCompletedNotificationBody;

class Importer extends \Filament\Actions\Imports\Importer
{
    use HasCompletedNotificationBody;
}
