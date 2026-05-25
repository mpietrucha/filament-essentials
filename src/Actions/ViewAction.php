<?php

namespace Mpietrucha\Filament\Essentials\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\ViewAction as FilamentViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Mpietrucha\Filament\Essentials\Actions\Concerns\ResolvesRecordFromRelation;

/**
 * @phpstan-type FormActionsResource class-string<Resource>
 * @phpstan-type FormActionsLivewire class-string<Component>
 */
class ViewAction extends FilamentViewAction
{
    use ResolvesRecordFromRelation;

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
        if ($action instanceof EditAction) {
            return $this->configureFormEditAction($action);
        }

        if ($action instanceof CreateAction) {
            return $this->configureFormCreateAction($action);
        }

        return parent::prepareModalAction($action);
    }

    protected function configureFormEditAction(EditAction $editAction): EditAction
    {
        $editAction->authorize(static function (Component $livewire, Model $record) use ($editAction): Response {
            if ($livewire instanceof RelationManager) {
                return $livewire->getAuthorizationResponse('update', $record);
            }

            /** @phpstan-ignore method.notFound, return.type */
            return $livewire->getDefaultActionAuthorizationResponse($editAction) ?? Response::allow();
        });

        return $editAction;
    }

    protected function configureFormCreateAction(CreateAction $createAction): CreateAction
    {
        $createAction->createAnother(false);

        return $createAction;
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
