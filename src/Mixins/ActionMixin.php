<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\Action;

/**
 * @phpstan-require-extends Action
 */
trait ActionMixin
{
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
