<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports\Concerns;

use Mpietrucha\Filament\Essentials\Actions\ImportAction;
use Mpietrucha\Filament\Essentials\Instance;

/**
 * @phpstan-require-extends \Filament\Actions\Imports\Importer
 */
trait HasAction
{
    public static function action(): ImportAction
    {
        $name = Instance::name($importer = static::class, 'Importer');

        $label = static::__('import.action.label', [
            'name' => $name,
        ]);

        return ImportAction::make($name)->label($label)->importer($importer);
    }
}
