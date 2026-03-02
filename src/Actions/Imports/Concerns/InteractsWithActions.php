<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports\Concerns;

use Mpietrucha\Filament\Essentials\Actions\ImportAction;
use Mpietrucha\Filament\Essentials\Actions\ImportBulkAction;
use Mpietrucha\Filament\Essentials\Concerns\Identifiable;

/**
 * @phpstan-require-extends \Filament\Actions\Imports\Importer
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
        return ImportBulkAction::make() |> static::configureAction(...);
    }

    protected static function configureAction(ImportAction $action): ImportAction
    {
        $name = static::identify('Importer');

        $label = __('filament-essentials::import.action.label', [
            'name' => $name,
        ]);

        $importer = static::class;

        return $action->name($name)->label($label)->importer($importer);
    }
}
