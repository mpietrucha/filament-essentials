<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports\Concerns;

use Filament\Actions\Imports\Models\Import;
use Mpietrucha\Filament\Essentials\Name;
use Mpietrucha\Utility\Str;

/**
 * @phpstan-require-extends \Filament\Actions\Imports\Importer
 */
trait InteractsWithCompletedNotificationBody
{
    public static function getCompletedNotificationBody(Import $import): string
    {
        $name = Name::get(static::class, 'Importer');

        $body = trans_choice('filament-essentials::import.completed', $import->successful_rows, [
            'name' => $name,
        ]);

        $failed = $import->getFailedRowsCount();

        if ($failed === 0) {
            return $body;
        }

        return Str::sprintf('%s %s', $body, trans_choice('filament-essentials::import.failed', $failed));
    }
}
