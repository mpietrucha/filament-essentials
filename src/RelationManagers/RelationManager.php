<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\RelationManagers;

use Mpietrucha\Filament\Essentials\RelationManagers\Concerns\GuessesRelationship;
use Mpietrucha\Filament\Essentials\RelationManagers\Concerns\GuessesResource;
use Mpietrucha\Filament\Essentials\RelationManagers\Concerns\InteractsWithActions;

class RelationManager extends \Filament\Resources\RelationManagers\RelationManager
{
    use GuessesRelationship;
    use GuessesResource;
    use InteractsWithActions;
}
