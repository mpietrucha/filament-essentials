<?php

namespace Mpietrucha\Filament\Essentials\Plugins\Concerns;

use Filament\Contracts\Plugin;
use Filament\Resources\Resource;

/**
 * @phpstan-require-implements Plugin
 *
 * @phpstan-type FilamentResource class-string<Resource>
 */
trait HasResource
{
    /**
     * @var null|FilamentResource
     */
    protected ?string $resource = null;

    /**
     * @param  FilamentResource  $resource
     */
    public function resource(string $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return null|FilamentResource
     */
    public function getResource(): ?string
    {
        return $this->resource;
    }
}
