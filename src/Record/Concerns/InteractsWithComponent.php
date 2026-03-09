<?php

namespace Mpietrucha\Filament\Essentials\Record\Concerns;

use Filament\Support\Components\Component;

/**
 * @phpstan-import-type RecordComponent from \Mpietrucha\Filament\Essentials\Record
 */
trait InteractsWithComponent
{
    /**
     * @param  RecordComponent  $component
     */
    public function __construct(protected Component $component)
    {
    }

    /**
     * @return RecordComponent
     */
    public function component(): Component
    {
        return $this->component;
    }
}
