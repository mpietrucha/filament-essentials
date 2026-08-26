<?php

namespace Mpietrucha\Filament\Essentials\Actions\Concerns;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Support\Exception\RuntimeException;
use Mpietrucha\Support\Instance;

/**
 * @phpstan-require-extends Action
 */
trait ResolvesRecordFromRelationship
{
    protected ?string $relationship = null;

    public function relationship(string $relationship): static
    {
        $this->relationship = $relationship;

        return $this;
    }

    public function getRelationship(): ?string
    {
        return $this->relationship;
    }

    public function getRecord(bool $withDefault = true): null|array|Model
    {
        $record = parent::getRecord($withDefault);

        if (! $record instanceof Model) {
            return $record;
        }

        $related = data_get($record, $relationship = $this->getRelationship());

        if (! $related instanceof Model) {
            RuntimeException::throw('Relation `%s` does not exist on %s model instance', $relationship, Instance::namespace($record));
        }

        return $related;
    }
}
