<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports\Concerns;

use Mpietrucha\Filament\Essentials\Actions\ImportAction;
use Mpietrucha\Filament\Essentials\Name;

/**
 * @phpstan-require-extends \Filament\Actions\Imports\Importer
 */
trait InteractsWithAction
{
    public static function action(): ImportAction
    {
        $name = Name::get($importer = static::class, 'Importer');

        $label = __('filament-essentials::import.action.label', [
            'name' => $name,
        ]);

        return ImportAction::make($name)->label($label)->importer($importer);
    }
}
