<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\Action;
use MatthiasMullie\Minify\JS;

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

                $cleanup(() => controller.abort());

                window.addEventListener('modal-closed', e => {
                    if (e.detail.id !== id) {
                        return;
                    }

                    $wire.unmountAction(true);

                    e.stopImmediatePropagation();
                }, { capture: true, signal: controller.signal });
            })
        JS;

        $this->extraModalWindowAttributes([
            'x-init' => new JS($script)->minify(),
        ], true);

        return $this;
    }
}
