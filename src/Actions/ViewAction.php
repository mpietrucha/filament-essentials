<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\ViewAction as FilamentViewAction;
use Livewire\Component;
use Mpietrucha\Filament\Essentials\Actions\Concerns\HasRelation;

/**
 * @method null|Component getLivewire()
 */
class ViewAction extends FilamentViewAction
{
    use HasRelation;

    protected bool|Closure $withEditAction = true;

    protected bool|Closure $withCreateAction = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extraModalFooterActions(function (): array {
            $container = $this->getLivewire()?->getFilamentActionsContainer();

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
        if ($callback === false || ! $action instanceof Action) {
            return null;
        }

        $this->getRecord() |> $action->record(...);

        if ($livewire = $this->getLivewire()) {
            $action->livewire($livewire);
        }

        if ($resource = $livewire?->getFilamentResource()) {
            $resource::configureActionSchema($action);
        }

        if ($action instanceof CreateAction) {
            $action->createAnother(false);
        }

        $action->alwaysCancelParentActions();

        value($callback, $action);

        return $action;
    }
}
