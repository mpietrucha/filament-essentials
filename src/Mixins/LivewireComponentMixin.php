<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

/**
 * @phpstan-require-extends Component
 */
trait LivewireComponentMixin
{
    public function getFilamentRecord(): ?Model
    {
        if ($this instanceof RelationManager) {
            /** @var null|Model */
            return $this->getOwnerRecord();
        }

        $record = method_exists($this, 'getRecord') ? $this->getRecord() : null;

        if (! $record instanceof Model) {
            return null;
        }

        return $record;
    }
}
