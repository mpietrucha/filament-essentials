<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Schemas\Components\Component;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Mpietrucha\Filament\Essentials\Record;

/**
 * @phpstan-require-extends TextColumn
 */
trait TextColumnMixin
{
    public function limitWithBadge(): static
    {
        return $this->state(function (Component $component, Model $record): null|HtmlString|string {
            $results = $this->getRelationshipResults($record) |> collect(...);

            if ($results->isEmpty()) {
                return null;
            }

            $name = Record::make(
                $component,
                $results->first()
            )->get($this->getFullAttributeName($record));

            if ($results->containsOneItem()) {
                return $name;
            }

            $remaning = $results->count() - 1;

            return new HtmlString(sprintf('%s%s', $name, $remaning));
        });
    }
}
