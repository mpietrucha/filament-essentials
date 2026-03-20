<?php

namespace Mpietrucha\Filament\Essentials\Resources;

use Filament\Resources\Resource as FilamentResource;
use Mpietrucha\Filament\Essentials\Resources\Concerns\InteractsWithActions;
use Mpietrucha\Filament\Essentials\Resources\Concerns\TitlesRecordById;

abstract class Resource extends FilamentResource
{
    use InteractsWithActions;
    use TitlesRecordById;
}
