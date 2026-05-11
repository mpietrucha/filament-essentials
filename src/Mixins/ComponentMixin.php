<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Mpietrucha\Filament\Essentials\Actions\CreateAction;
use Mpietrucha\Filament\Essentials\Actions\EditAction;
use Mpietrucha\Filament\Essentials\Actions\ViewAction;
use Mpietrucha\Support\Exception\RuntimeException;

/**
 * @phpstan-require-extends Component
 *
 * @phpstan-type FilamentResource class-string<Resource>
 */
trait ComponentMixin
{
    /**
     * @return null|FilamentResource
     */
    public static function getFilamentResource(): ?string
    {
        $component = static::class;

        /** @phpstan-ignore function.impossibleType */
        if (is_a($component, RelationManager::class, true)) {
            /** @var null|FilamentResource */
            return $component::getRelatedResource();
        }

        $resource = method_exists($component, 'getResource') ? $component::getResource() : null;

        if (! is_string($resource)) {
            return null;
        }

        if (! is_a($resource, Resource::class, true)) {
            return null;
        }

        return $resource;
    }

    /**
     * @return FilamentResource
     */
    public static function getActionsResource(): string
    {
        return static::getFilamentResource() ?? RuntimeException::throw('Related resource cannot be empty');
    }

    public static function getViewAction(?string $relation = null): ViewAction
    {
        $viewAction = static::getActionsResource()::getViewAction($relation);

        $viewAction->withFormActionsLivewire(static::class);

        return $viewAction;
    }

    public static function getEditAction(?string $relation = null): EditAction
    {
        return static::getActionsResource()::getEditAction($relation);
    }

    public static function getCreateAction(?string $relation = null): CreateAction
    {
        return static::getActionsResource()::getCreateAction($relation);
    }

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
}
