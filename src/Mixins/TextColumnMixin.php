<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Mpietrucha\Filament\Essentials\Record;

/**
 * @phpstan-require-extends TextColumn
 */
trait TextColumnMixin
{
    public function limitBadge(): static
    {
        return $this->state(function (Model $record): null|HtmlString|string {
            $results = $this->getRelationshipResults($record) |> collect(...);

            if ($results->isEmpty()) {
                return null;
            }

            $name = Record::make(
                $results->first()
            )->get($this->getFullAttributeName($record));

            if (! is_string($name)) {
                return null;
            }

            if ($results->containsOneItem()) {
                return $name;
            }

            $remaning = (string) $results->count() - 1;

            return new HtmlString(sprintf('%s%s', $name, $remaning));
        });
    }
}
