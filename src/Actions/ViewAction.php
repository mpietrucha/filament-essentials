<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Actions;

use Filament\Actions\Action;
use Filament\Actions\ViewAction as FilamentViewAction;
use Filament\Resources\Resource;
use Livewire\Component;
use Mpietrucha\Filament\Essentials\Actions\Concerns\HasRelation;

/**
 * @phpstan-type FilamentResource class-string<Resource>
 */
class ViewAction extends FilamentViewAction
{
    use HasRelation;

    /**
     * @var null|FilamentResource
     */
    protected ?string $formActionsResource = null;

    protected bool $withEditAction = true;

    protected bool $withCreateAction = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extraModalFooterActions(function (): array {
            $resource = $this->formActionsResource;

            $actions = [];

            if ($resource === null) {
                return $actions;
            }

            if ($this->withEditAction) {
                $actions[] = $resource::getEditAction() |> $this->configureFormAction(...);
            }

            if ($this->withCreateAction) {
                $actions[] = $resource::getCreateAction() |> $this->configureFormAction(...);
            }

            return $actions;
        });
    }

    /**
     * @param  FilamentResource  $formActionsResource
     */
    public function withFormActionsResource(string $formActionsResource): static
    {
        $this->formActionsResource = $formActionsResource;

        return $this;
    }

    public function withEditAction(bool $withEditAction = true): static
    {
        $this->withEditAction = $withEditAction;

        return $this;
    }

    final public function withoutEditAction(): static
    {
        return $this->withEditAction(false);
    }

    public function withCreateAction(bool $withCreateAction = true): static
    {
        $this->withCreateAction = $withCreateAction;

        return $this;
    }

    final public function withoutCreateAction(): static
    {
        return $this->withCreateAction(false);
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

    protected function configureFormAction(Action $action): Action
    {
        $action->cancelParentActions();

        $action->extraModalWindowAttributes([
            'x-init' => <<<'JS'
                $nextTick(() => {
                    const wire = $wire;
                    const id = $el.closest('.fi-modal')?.id;
                    if (!id) return;
                    const controller = new AbortController();
                    window.addEventListener('modal-closed', (e) => {
                        if (e.detail.id !== id) return;
                        e.stopImmediatePropagation();
                        wire.unmountAction(true);
                    }, { capture: true, signal: controller.signal });
                    $cleanup(() => controller.abort());
                })
                JS,
        ]);

        $this->getRecord() |> $action->record(...);

        /** @var null|Component $livewire */
        $livewire = $this->getLivewire();

        if ($livewire) {
            $action->livewire($livewire);
        }

        $resource = $this->formActionsResource ?? $livewire?->getFilamentResource();

        if ($resource) {
            $resource::configureActionSchema($action);
        }

        return $action;
    }
}
