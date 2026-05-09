<?php

namespace Mpietrucha\Filament\Essentials\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\ViewAction as FilamentViewAction;
use Filament\Resources\Resource;
use Livewire\Component;
use Mpietrucha\Filament\Essentials\Actions\Concerns\HasRelation;

/**
 * @method null|Component getLivewire()
 *
 * @phpstan-type FilamentResource class-string<Resource>
 */
class ViewAction extends FilamentViewAction
{
    use HasRelation;

    /**
     * @var null|class-string
     */
    protected ?string $formActionsContainer = null;

    protected bool|Closure $withEditAction = true;

    protected bool|Closure $withCreateAction = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extraModalFooterActions(function (): array {
            $container = $this->getFormActionsContainer();

            $actions = [];

            if ($container === null) {
                return $actions;
            }

            if ($callback = $this->withEditAction) {
                $actions[] = $this->buildFormAction($container::getEditAction(), $callback);
            }

            if ($callback = $this->withCreateAction) {
                $actions[] = $this->buildFormAction($container::getCreateAction(), $callback);
            }

            return array_filter($actions);
        });
    }

    /**
     * @param  class-string  $formActionsContainer
     */
    public function withFormActionsContainer(string $formActionsContainer): static
    {
        if (! method_exists($formActionsContainer, 'getEditAction')) {
            return $this;
        }

        if (! method_exists($formActionsContainer, 'getCreateAction')) {
            return $this;
        }

        if (! method_exists($formActionsContainer, 'configureActionSchema')) {
            return $this;
        }

        $this->formActionsContainer = $formActionsContainer;

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
        if ($action instanceof EditAction) {
            return $action;
        }

        if ($action instanceof CreateAction) {
            return $action;
        }

        return parent::prepareModalAction($action);
    }

    protected function buildFormAction(mixed $action, mixed $callback = null): ?Action
    {
        if ($callback === false) {
            return null;
        }

        if (! $action instanceof Action) {
            return null;
        }

        $action->cancelParentActions();

        $action->extraModalWindowAttributes([

        ]);

        $this->getRecord() |> $action->record(...);

        if ($livewire = $this->getLivewire()) {
            $action->livewire($livewire);
        }

        if ($container = $this->getFormActionsContainer()) {
            $container::configureActionSchema($action);
        }

        value($callback, $action);

        return $action;
    }

    /**
     * @return null|class-string
     */
    protected function getFormActionsContainer(): ?string
    {
        if ($container = $this->formActionsContainer) {
            return $container;
        }

        /** @var null|FilamentResource */
        return $this->getLivewire()?->getFilamentResource();
    }
}
