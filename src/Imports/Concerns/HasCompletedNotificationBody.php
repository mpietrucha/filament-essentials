<?php

namespace Mpietrucha\Filament\Essentials\Imports\Concerns;

use Filament\Actions\Imports\Models\Import;
use Mpietrucha\Filament\Essentials\Instance;
use Mpietrucha\Laravel\Essentials\Package\Translations\Concerns\InteractsWithTranslations;

trait HasCompletedNotificationBody
{
    use InteractsWithTranslations;

    public static function getCompletedNotificationBody(Import $import): string
    {
        $parameters = [
            'name' => Instance::name(__CLASS__, 'Importer'),
        ];

        $body = static::tc('importer.completed', $import->successful_rows, $parameters);

        $failed = $import->getFailedRowsCount();

        if ($failed === 0) {
            return $body;
        }

        return Str::sprintf('%s %s', $body, static::tc('importer.failed', $failed));
    }
}
