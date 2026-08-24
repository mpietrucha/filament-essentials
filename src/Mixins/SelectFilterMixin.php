<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasSelectTitleWithAvatar;

/**
 * @phpstan-require-extends SelectFilter
 */
trait SelectFilterMixin
{
    use HasSelectTitleWithAvatar;

    public function withAvatars(?string $attribute = null): static
    {
        return static::getSelectTitleWithAvatar(
            $attribute,
            $this->getRelationshipTitleAttribute(...)
        ) |> $this->getOptionLabelFromRecordUsing(...);
    }

    public function queryThroughRelationship(): static
    {
        $relationship = Str::beforeLast($attribute = $this->getAttribute(), $indicator = '.');
        $attribute = Str::afterLast($attribute, $indicator);

        return $this->query(fn (Builder $builder, array $data): Builder => $builder->whereHas(
            $relationship,
            function (Builder $builder) use ($attribute, $data): void {
                /** @phpstan-ignore method.nonObject, argument.templateType */
                $value = collect($data)->flatten()->unless($isMultiple = $this->isMultiple())->first();

                if (blank($value)) {
                    return;
                }

                $builder->{$isMultiple ? 'whereIn' : 'where'}($attribute, $value);
            }
        ));
    }
}
