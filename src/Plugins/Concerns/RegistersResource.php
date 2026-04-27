<?php

namespace Mpietrucha\Filament\Essentials\Plugins\Concerns;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Resources\Resource;
use Mpietrucha\Filament\Essentials\Resources\ResourceGuesser;
use Mpietrucha\Support\Exception\InvalidArgumentException;

/**
 * @phpstan-require-implements Plugin
 *
 * @phpstan-type FilamentResource class-string<Resource>
 */
trait RegistersResource
{
    /**
     * @var null|FilamentResource
     */
    protected ?string $resource = null;

    protected bool $registerDefaultResource = false;

    /**
     * @param  FilamentResource  $resource
     */
    public function resource(string $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    public function registerDefaultResource(): static
    {
        $this->registerDefaultResource = true;

        return $this;
    }

    public function dontRegisterDefaultResource(): static
    {
        $this->registerDefaultResource = false;

        return $this;
    }

    public function register(Panel $panel): void
    {
        static::registerResource($panel, $this->resource, $this->registerDefaultResource);
    }

    protected static function registerResource(Panel $panel, mixed $resource, bool $register = false): bool
    {
        if ($resource === null) {
            return false;
        }

        if (! is_string($resource) || ! is_a($resource, Resource::class, true)) {
            InvalidArgumentException::throw('The resource to be registered must be %s class string', Resource::class);
        }

        if ($register === false) {
            ResourceGuesser::registerGuessableResource($resource);

            return false;
        }

        $panel->resources([$resource]);

        return true;
    }
}
