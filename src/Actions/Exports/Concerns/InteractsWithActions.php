<?php

namespace Mpietrucha\Filament\Essentials\Actions\Exports\Concerns;

use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Exporter;
use Mpietrucha\Filament\Essentials\Concerns\Identifiable;

/**
 * @phpstan-require-extends Exporter
 */
trait InteractsWithActions
{
    use Identifiable;

    public static function action(): ExportAction
    {
        return ExportAction::make() |> static::configureAction(...);
    }

    protected static function configureAction(ExportAction $exportAction): ExportAction
    {
        $name = static::identify('Exporter');

        __('filament-essentials::export.action.label', [
            'name' => $name,
        ]) |> $exportAction->label(...);

        return $exportAction->name($name)->exporter(static::class);
    }
}
