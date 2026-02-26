<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports\Concerns;

use Filament\Actions\Imports\Models\Import;
use Mpietrucha\Filament\Essentials\Instance;
use Mpietrucha\Laravel\Essentials\Package\Translations\Concerns\InteractsWithTranslations;
use Mpietrucha\Utility\Str;

/**
 * @phpstan-require-extends \Filament\Actions\Imports\Importer
 */
trait HasCompletedNotificationBody
{
    use InteractsWithTranslations;

    public static function getCompletedNotificationBody(Import $import): string
    {
        $parameters = [
            'name' => Instance::name(static::class, 'Importer'),
        ];

        $body = static::tc('import.completed', $import->successful_rows, $parameters);

        $failed = $import->getFailedRowsCount();

        if ($failed === 0) {
            return $body;
        }

        return Str::sprintf('%s %s', $body, static::tc('import.failed', $failed));
    }
}
