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

    public static function getCompletedNotificationName(): string
    {
        return static::identify('Exporter');
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = trans_choice('filament-essentials::filament.export.completed', $export->successful_rows, [
            'name' => static::getCompletedNotificationName(),
        ]);

        $failed = $export->getFailedRowsCount();

        if ($failed === 0) {
            return $body;
        }

        return sprintf('%s %s', $body, trans_choice('filament-essentials::filament.export.failed', $failed));
    }
}
