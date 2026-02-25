<?php

namespace Mpietrucha\Filament\Essentials\Imports\Concerns;

use Filament\Actions\Imports\Models\Import;
use Mpietrucha\Laravel\Essentials\Package\Translations\Concerns\InteractsWithTranslations;

trait HasCompletedNotificationBody
{
    use InteractsWithTranslations;

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = static::__('Your :name import has compleded and :successful :rows imported.');

        $body = static::__(':Failed :rows failed to import.');
    }
}
