<?php

namespace Mpietrucha\Filament\Essentials\Resources;

use Mpietrucha\Filament\Essentials\Resources\Concerns\HasIncrementingLabel;
use Mpietrucha\Filament\Essentials\Resources\Concerns\InteractsWithActions;

abstract class Resource extends \Filament\Resources\Resource
{
    use HasIncrementingLabel, InteractsWithActions;
}
