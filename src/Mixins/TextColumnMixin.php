<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\View;
use Mpietrucha\Utility\Collection;
use Mpietrucha\Utility\Data;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-require-extends \Filament\Tables\Columns\TextColumn
 */
trait TextColumnMixin
{
    public function limitWithBadge(): static
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

            $badge = $resuls->count() - 1 |> View::textColumnBadge(...);

            return View::html($name, $badge);
        });
    }
}
