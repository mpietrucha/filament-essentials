<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Mpietrucha\Filament\Essentials\Blade;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\BuildsMoneyWithDiscount;

/**
 * @phpstan-require-extends TextColumn
 */
trait TextColumnMixin
{
    use BuildsMoneyWithDiscount;

    public function withLimitBadge(): static
    {
        return $this->state(function (Model $record): null|HtmlString|string {
            $results = $this->getRelationshipResults($record) |> collect(...);

            if ($results->isEmpty()) {
                return null;
            }

            /** @var mixed $name */
            $name = $this->getFullAttributeName($record) |> $results->value(...);

            $results->shift();

            if (! is_string($name)) {
                return null;
            }

            if ($results->isEmpty()) {
                return $name;
            }

            return new HtmlString(sprintf(
                '%s%s',
                $name,
                Blade::renderSuffixBadge(sprintf('+%s', $results->count()))
            ));
        });
    }
}
