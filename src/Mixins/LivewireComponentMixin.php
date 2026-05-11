<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Mpietrucha\Support\Instance;

/**
 * @phpstan-require-extends Component
 *
 * @phpstan-type FilamentResource class-string<Resource>
 */
trait LivewireComponentMixin
{
    public function getFilamentRecord(): ?Model
    {
        if ($this instanceof RelationManager) {
            /** @var null|Model */
            return $this->getOwnerRecord();
        }

        $record = method_exists($this, 'getRecord') ? $this->getRecord() : null;

        if (! $record instanceof Model) {
            return null;
        }

        return $record;
    }

    /**
     * @return null|FilamentResource
     */
    public function getFilamentResource(): ?string
    {
        if ($this instanceof RelationManager) {
            /** @var FilamentResource */
            return $this->getRelatedResource();
        }

        $resource = method_exists($this, 'getResource') ? $this->getResource() : null;

        if (! is_string($resource)) {
            return null;
        }

        if (! is_a($resource, Resource::class, true)) {
            return null;
        }

        return $resource;
    }

    /**
     * @return null|FilamentResource|class-string<RelationManager>
     */
    public function getFilamentActionsContainer(): ?string
    {
        $container = $this instanceof RelationManager ? $this : $this->getFilamentResource();

        if ($container === null) {
            return null;
        }

        /** @var FilamentResource|class-string<RelationManager> */
        return Instance::namespace($container);
    }
}
