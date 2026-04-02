<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasAvatarConfigurator;
use Mpietrucha\Support\Str;

/**
 * @phpstan-require-extends SelectFilter
 */
trait SelectFilterMixin
{
    use HasAvatarConfigurator;

    public function withAvatars(?string $attribute = null): static
    {
        return static::getAvatarConfigurator(
            $attribute,
            $this->getRelationshipTitleAttribute(...)
        ) |> $this->getOptionLabelFromRecordUsing(...);
    }

    public function queryRelationship(): static
    {
        $relationship = Str::beforeLast(
            $attribute = $this->getAttribute(),
            $indicator = '.'
        );

        $attribute = Str::afterLast($attribute, $indicator);

        return $this->query(fn (Builder $query, array $data): Builder => $query->whereHas(
            $relationship,
            function (Builder $query) use ($attribute, $data): void {
                $value = collect($data)->flatten();

                if (false === $isMultiple = $this->isMultiple()) {
                    $value = $value->first();
                }

                if ($value === null) {
                    return;
                }

                $query->{$isMultiple ? 'whereIn' : 'where'}($attribute, $value);
            }
        ));
    }
}
