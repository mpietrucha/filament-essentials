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

    public static function getActionName(): string
    {
        return static::identify('Exporter');
    }

    protected static function configureAction(ExportAction $exportAction): ExportAction
    {
        $exportAction->exporter(static::class);

        static::getActionName() |> $exportAction->name(...);

        return $exportAction;
    }
}
