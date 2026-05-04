<?php

namespace Mpietrucha\Filament\Essentials\Actions\Concerns;

use Closure;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

/**
 * @phpstan-require-extends Action
 */
trait TransformsRecord
{
    protected ?Closure $transformRecordUsing = null;

    public function relationship(string $relationship): static
    {
        return $this->transformRecordUsing(static function (Model $record) use ($relationship) {
            return data_get($record, $relationship);
        });
    }

    public function transformRecordUsing(Closure $transformRecordUsing): static
    {
        $this->transformRecordUsing = $transformRecordUsing;

        return $this;
    }

    protected function getTransformedRecord(Model $record): ?Model
    {
        $transformRecordUsing = $this->transformRecordUsing;

        if ($transformRecordUsing === null) {
            return $record;
        }

        $record = value($transformRecordUsing, $record);

        return $record instanceof Model ? $record : null;
    }
}
