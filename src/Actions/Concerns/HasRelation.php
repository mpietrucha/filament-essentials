<?php

namespace Mpietrucha\Filament\Essentials\Actions\Concerns;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Support\Exception\RuntimeException;
use Mpietrucha\Support\Instance;

/**
 * @phpstan-require-extends Action
 */
trait HasRelation
{
    protected ?string $relation = null;

    public function relation(string $relation): static
    {
        $this->relation = $relation;

        return $this;
    }

    public function getRecord(bool $withDefault = true): null|array|Model
    {
        $record = parent::getRecord($withDefault);

        if (! $record instanceof Model) {
            return $record;
        }

        $related = data_get($record, $relation = $this->relation);

        if (! $related instanceof Model) {
            RuntimeException::throw('Relation `%s` does not exist on %s model instance', $relation, Instance::namespace($record));
        }

        return $related;
    }
}
