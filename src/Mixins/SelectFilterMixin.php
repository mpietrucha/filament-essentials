<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasAvatarBuilderClosure;
use Mpietrucha\Support\Str;

/**
 * @phpstan-require-extends SelectFilter
 */
trait SelectFilterMixin
{
    use HasAvatarBuilderClosure;

    public function withAvatars(?string $attribute = null): static
    {
        return static::getAvatarBuilderClosure(
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

        return $this->query(fn (Builder $builder, array $data): Builder => $builder->whereHas(
            $relationship,
            function (Builder $builder) use ($attribute, $data): void {
                $value = collect($data)->flatten();

                if (false === $isMultiple = $this->isMultiple()) {
                    $value = $value->first();
                }

                if (blank($value)) {
                    return;
                }

                $builder->{$isMultiple ? 'whereIn' : 'where'}($attribute, $value);
            }
        ));
    }
}
