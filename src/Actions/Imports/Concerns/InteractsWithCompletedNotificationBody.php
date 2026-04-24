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

    public static function getCompletedNotificationName(): string
    {
        return static::identify('Importer');
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = trans_choice('filament-essentials::filament.import.completed', $import->successful_rows, [
            'name' => static::getCompletedNotificationName(),
        ]);

        $failed = $import->getFailedRowsCount();

        if ($failed === 0) {
            return $body;
        }

        return sprintf('%s %s', $body, trans_choice('filament-essentials::filament.import.failed', $failed));
    }
}
