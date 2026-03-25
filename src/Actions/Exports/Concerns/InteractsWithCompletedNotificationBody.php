<?php

namespace Mpietrucha\Filament\Essentials\Actions\Exports\Concerns;

use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Mpietrucha\Filament\Essentials\Concerns\Identifiable;

/**
 * @phpstan-require-extends Exporter
 */
trait InteractsWithCompletedNotificationBody
{
    use Identifiable;

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = trans_choice('filament-essentials::export.completed', $export->successful_rows, [
            'name' => static::identify('Exporter'),
        ]);

        $failed = $export->getFailedRowsCount();

        if ($failed === 0) {
            return $body;
        }

        return sprintf('%s %s', $body, trans_choice('filament-essentials::export.failed', $failed));
    }
}
