<?php

namespace Mpietrucha\Filament\Essentials\Actions\Concerns;

use Closure;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Support\Exception\RuntimeException;

/**
 * @phpstan-require-extends Action
 */
trait TransformsRecord
{
    protected null|Closure|string $recordTransformer = null;

    public function transformRecord(Closure|string $recordTransformer): static
    {
        $this->recordTransformer = $recordTransformer;

        return $this;
    }

    protected function getTransformedRecord(Model $record): Model
    {
        $transformer = $this->recordTransformer;

        $record = match (true) {
            is_string($transformer) => data_get($record, $transformer),
            default => value($transformer, $record, $record)
        };

        if (! $record instanceof Model) {
            RuntimeException::throw('Record transformer must return Model');
        }

        return $record;
    }
}
