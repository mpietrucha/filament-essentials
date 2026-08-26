<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Actions;

use Filament\Actions\EditAction as FilamentEditAction;
use Mpietrucha\Filament\Essentials\Actions\Concerns\ResolvesRecordFromRelationship;

class EditAction extends FilamentEditAction
{
    use ResolvesRecordFromRelationship;
}
