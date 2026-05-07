<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Actions;

use Filament\Actions\CreateAction as FilamentCreateAction;
use Mpietrucha\Filament\Essentials\Actions\Concerns\HasRelation;

class CreateAction extends FilamentCreateAction
{
    use HasRelation;
}
