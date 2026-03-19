<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports\Concerns;

use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Mpietrucha\Filament\Essentials\Concerns\Identifiable;

/**
 * @phpstan-require-extends Importer
 */
trait InteractsWithCompletedNotificationBody
{
    use Identifiable;

    public static function getCompletedNotificationBody(Import $import): string
    {
        $name = static::identify('Importer');

        $body = trans_choice('filament-essentials::import.completed', $import->successful_rows, [
            'name' => $name,
        ]);

        $failed = $import->getFailedRowsCount();

        if ($failed === 0) {
            return $body;
        }

        return sprintf('%s %s', $body, trans_choice('filament-essentials::import.failed', $failed));
    }
}
