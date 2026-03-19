<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

/**
 * @phpstan-require-extends TextColumn
 */
trait TextColumnMixin
{
    public function limitBadge(): static
    {
        return $this->state(function (Model $record) {
            $results = $this->getRelationshipResults($record) |> collect(...);

            if ($results->isEmpty()) {
                return null;
            }

            $name = data_get(
                $result = $results->first(),
                $this->getFullAttributeName($record)
            );

            if ($name === null) {
                return null;
            }

            if ($results->containsOneItem()) {
                return $name;
            }

            return new HtmlString($name . (string) $results->count() - 1);
        });
    }
}
