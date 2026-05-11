<?php

namespace Mpietrucha\Filament\Essentials\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\ViewAction as FilamentViewAction;
use Filament\Resources\Resource;
use Livewire\Component;
use Mpietrucha\Filament\Essentials\Actions\Concerns\HasRelation;

/**
 * @phpstan-type FormActionsResource class-string<Resource>
 * @phpstan-type FormActionsLivewire class-string<Component>
 */
class ViewAction extends FilamentViewAction
{
    use HasRelation;

    /**
     * @var null|FormActionsResource
     */
    protected ?string $formActionsResource = null;

    /**
     * @var null|FormActionsLivewire
     */
    protected ?string $formActionsLivewire = null;

    protected bool|Closure $withEditAction = true;

    protected bool|Closure $withCreateAction = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extraModalFooterActions(function (): array {
            $container = $this->formActionsLivewire ?? $this->formActionsResource;

            $actions = [];

            if ($container === null) {
                return $actions;
            }

            if ($callback = $this->withEditAction) {
                $actions[] = $this->configureFormAction($container::getEditAction(), $callback);
            }

            if ($callback = $this->withCreateAction) {
                $actions[] = $this->configureFormAction($container::getCreateAction(), $callback);
            }

            return $actions;
        });
    }

    /**
     * @param  FormActionsResource  $formActionsResource
     */
    public function withFormActionsResource(string $formActionsResource): static
    {
        $this->formActionsResource = $formActionsResource;

        return $this;
    }

    /**
     * @param  FormActionsLivewire  $formActionsLivewire
     */
    public function withFormActionsLivewire(string $formActionsLivewire): static
    {
        $this->formActionsLivewire = $formActionsLivewire;

        return $this;
    }

    public function withEditAction(?Closure $withEditAction = null): static
    {
        $this->withEditAction = $withEditAction ?? true;

        return $this;
    }

    public function withoutEditAction(): static
    {
        $this->withEditAction = false;

        return $this;
    }

    public function withCreateAction(?Closure $withCreateAction = null): static
    {
        $this->withCreateAction = $withCreateAction ?? true;

        return $this;
    }

    public function withoutCreateAction(): static
    {
        $this->withCreateAction = false;

        return $this;
    }

    #[\Override]
    public function prepareModalAction(Action $action): Action
    {
        if ($action instanceof CreateAction) {
            return $action;
        }

        if ($action instanceof EditAction) {
            return $action;
        }

        return parent::prepareModalAction($action);
    }

    protected function configureFormAction(Action $action, bool|Closure $callback): Action
    {
        if (null !== $livewire = $this->getLivewire()) {
            /** @var Component $livewire */
            $action->livewire($livewire);
        }

        if ($resource = $this->formActionsResource) {
            $resource::configureActionSchema($action);
        }

        $this->getRecord() |> $action->record(...);

        $action->alwaysCancelParentActions();

        value($callback, $action);

        return $action;
    }
}
