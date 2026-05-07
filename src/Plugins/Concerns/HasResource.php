<?php

namespace Mpietrucha\Filament\Essentials\Plugins\Concerns;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Resources\Resource;
use Illuminate\Support\Arr;
use Mpietrucha\Support\Exception\InvalidArgumentException;
use Mpietrucha\Support\Exception\RuntimeException;

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
     * @var FilamentResource
     */
    protected string $defaultResource;

    protected bool $shouldRegisterResource = true;

    /**
     * @param  FilamentResource  $resource
     */
    public function resource(string $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    public function shouldRegisterResource(bool $shouldRegisterResource = true): static
    {
        $this->shouldRegisterResource = $shouldRegisterResource;

        return $this;
    }

    public function dontRegisterResource(): static
    {
        return $this->shouldRegisterResource(false);
    }

    /**
     * @return FilamentResource
     */
    public function getDefaultResource(): string
    {
        /** @phpstan-ignore nullCoalesce.property */
        return $this->defaultResource ?? RuntimeException::throw('Plugin default resource must be initialized');
    }

    /**
     * @return FilamentResource
     */
    public function getResource(): string
    {
        $defaultResource = $this->getDefaultResource();

        if (null === $resource = $this->resource) {
            return $defaultResource;
        }

        if (! is_a($resource, $defaultResource, true)) {
            InvalidArgumentException::throw('Plugin resource must be `%s`', $defaultResource);
        }

        return $resource;
    }

    protected function registerResource(Panel $panel): void
    {
        if ($this->shouldRegisterResource === false) {
            return;
        }

        /** @phpstan-ignore argument.type */
        $this->getResource() |> Arr::wrap(...) |> $panel->resources(...);
    }
}
