<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports\Concerns;

use Mpietrucha\Filament\Essentials\Actions\ImportAction;
use Mpietrucha\Filament\Essentials\Actions\ImportBulkAction;
use Mpietrucha\Filament\Essentials\Name;

/**
 * @phpstan-require-extends \Filament\Actions\Imports\Importer
 */
trait InteractsWithAction
{
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
        $name = Name::get($importer = static::class, 'Importer');

        $label = __('filament-essentials::import.action.label', [
            'name' => $name,
        ]);

        return $action->name($name)->label($label)->importer($importer);
    }
}
