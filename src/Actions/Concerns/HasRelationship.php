<?php

namespace Mpietrucha\Filament\Essentials\Actions\Concerns;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Support\Exception\RuntimeException;

/**
 * @phpstan-require-extends Action
 */
trait HasRelationship
{
    protected ?string $relationship = null;

    public function relationship(string $relationship): static
    {
        $this->relationship = $relationship;

        return $this;
    }

    protected function getRelatedRecord(Model $record): Model
    {
        if (null === $relationship = $this->relationship) {
            return $record;
        }

        $record = $record->$relationship;

        if (! $record instanceof Model) {
            RuntimeException::throw('Undefined relation `%s`', $relationship);
        }

        return $record;
    }
}
