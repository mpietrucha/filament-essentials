<?php

namespace Mpietrucha\Filament\Essentials\RelationManagers;

use Mpietrucha\Filament\Essentials\RelationManagers\Concerns\GuessesRelationship;
use Mpietrucha\Filament\Essentials\RelationManagers\Concerns\GuessesResource;
use Mpietrucha\Filament\Essentials\RelationManagers\Concerns\InteractsWithActions;

abstract class RelationManager extends \Filament\Resources\RelationManagers\RelationManager
{
    use GuessesRelationship, GuessesResource, InteractsWithActions;
}
