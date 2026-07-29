<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Guava\FilamentModalRelationManagers\Actions\RelationManagerAction;

/**
 * @phpstan-require-extends RelationManagerAction
 */
trait RelationManagerActionMixin
{
    public function overlaysNestedModals(): static
    {
        $this->extraModalWindowAttributes([
            'x-hides-for-nested-modals' => true,
            'class' => 'fi-relation-manager-modal',
        ]);

        return $this;
    }
}
