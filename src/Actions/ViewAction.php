<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Actions;

use Filament\Actions\ViewAction as FilamentViewAction;
use Mpietrucha\Filament\Essentials\Actions\Concerns\HasRelation;

class ViewAction extends FilamentViewAction
{
    use HasRelation;
}
