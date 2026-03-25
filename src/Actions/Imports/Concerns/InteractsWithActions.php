<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports\Concerns;

use Filament\Actions\Imports\Importer;
use Mpietrucha\Filament\Essentials\Actions\ImportAction;
use Mpietrucha\Filament\Essentials\Actions\ImportBulkAction;

/**
 * @phpstan-require-extends Importer
 */
trait InteractsWithActions
{
    public static function action(): ImportAction
    {
        return ImportAction::make() |> static::configureAction(...);
    }

    public static function bulkAction(): ImportBulkAction
    {
        return ImportBulkAction::make() |> static::configureBulkAction(...);
    }

    protected static function configureBulkAction(ImportBulkAction $importBulkAction): ImportBulkAction
    {
        static::configureAction($importBulkAction);

        return $importBulkAction;
    }

    protected static function configureAction(ImportAction $importAction): ImportAction
    {
        $name = static::identify('Importer');

        return $importAction->name($name)->importer(static::class);
    }
}
