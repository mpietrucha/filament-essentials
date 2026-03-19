<?php

namespace Mpietrucha\Filament\Essentials\Resources;

use Filament\Resources\Resource as FilamentResource;
use Mpietrucha\Filament\Essentials\Resources\Concerns\HasNumberedTitle;
use Mpietrucha\Filament\Essentials\Resources\Concerns\InteractsWithActions;

abstract class Resource extends FilamentResource
{
    use HasNumberedTitle;
    use InteractsWithActions;
}
