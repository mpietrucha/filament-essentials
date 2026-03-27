<?php

namespace Mpietrucha\Filament\Essentials\Resources;

use Filament\Resources\Resource as FilamentResource;
use Filament\Resources\ResourceConfiguration;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Resources\Concerns\InteractsWithActions;

/**
 * @template TModel of Model = Model
 * @template TConfiguration of ResourceConfiguration = ResourceConfiguration
 *
 * @extends FilamentResource<TModel, TConfiguration>
 */
abstract class Resource extends FilamentResource
{
    use InteractsWithActions;
}
