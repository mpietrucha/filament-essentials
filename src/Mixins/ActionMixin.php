<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\Action;
use Filament\Support\Enums\Operation;
use Mpietrucha\Filament\Essentials\Actions\ActionOperations;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Support\Exception\RuntimeException;

/**
 * @phpstan-require-extends Action
 */
trait ActionMixin
{
    public function operation(Operation $operation): static
    {
        ActionOperations::set($this, $operation);

        return $this;
    }

    public function getOperation(): Operation
    {
        return ActionOperations::get($this) ?? RuntimeException::throw('Cannot resolve operation for this Action instance');
    }

    public function alwaysCancelParentActions(): static
    {
        $this->cancelParentActions();

        $this->extraModalWindowAttributes(['x-always-cancel-parent-actions' => true], true);

        return $this;
    }

    public function imageModalIcon(?string $attribute = null): static
    {
        $this->extraModalWindowAttributes([
            'class' => 'fi-modal-image-icon',
        ]);

        Record::avatar($attribute) |> $this->modalIcon(...);

        return $this;
    }
}
