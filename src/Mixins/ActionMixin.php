<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Operation;
use Mpietrucha\Support\Exception\RuntimeException;

/**
 * @phpstan-require-extends Action
 */
trait ActionMixin
{
    public function getOperation(): Operation
    {
        return match (true) {
            $this instanceof CreateAction => Operation::Create,
            $this instanceof EditAction => Operation::Edit,
            $this instanceof ViewAction => Operation::View,
            default => RuntimeException::throw('Cannot resolve operation for this Action instance'),
        };
    }

    public function alwaysCancelParentActions(): static
    {
        $this->cancelParentActions();

        $script = <<<'JS'
            $nextTick(() => {
                const id = $el.closest('.fi-modal')?.id;

                if (id === undefined) {
                    return;
                }

                const controller = new AbortController();

                window.addEventListener('modal-closed', e => {
                    if (e.detail.id !== id) {
                        return;
                    }

                    controller.abort();

                    $wire.unmountAction(true);

                    e.stopImmediatePropagation();
                }, { capture: true, signal: controller.signal });
            })
        JS;

        $this->extraModalWindowAttributes(['x-init' => $script], true);

        return $this;
    }
}
