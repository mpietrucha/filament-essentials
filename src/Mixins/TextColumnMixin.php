<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Component;
use Mpietrucha\Filament\Essentials\Html;
use Mpietrucha\Utility\Collection;
use Mpietrucha\Utility\Data;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-require-extends \Filament\Tables\Columns\TextColumn
 */
trait TextColumnMixin
{
    public function firstWithLimitBadge(): static
    {
        return $this->state(function (Model $record) {
            $results = $this->getRelationshipResults($record) |> Collection::create(...);

            if ($results->isEmpty()) {
                return null;
            }

            $name = Data::get(
                $result = $results->first(),
                $this->getFullAttributeName($record)
            );

            if (Type::null($name)) {
                return null;
            }

            if ($results->containsOneItem()) {
                return $name;
            }

            $badge = $results->count() - 1 |> Component::renderTextColumnBadge(...);

            return Html::join($name, $badge);
        });
    }
}
