<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports\Concerns;

use Filament\Actions\Imports\Importer;
use Mpietrucha\Filament\Essentials\Actions\ImportAction;
use Mpietrucha\Filament\Essentials\Actions\ImportBulkAction;
use Mpietrucha\Filament\Essentials\Concerns\Identifiable;

/**
 * @phpstan-require-extends Importer
 */
trait InteractsWithActions
{
    use Identifiable;

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

        $label = __('filament-essentials::import.action.label', [
            'name' => $name,
        ]);

        $importer = static::class;

        return $importAction->name($name)->label($label)->importer($importer);
    }
}
