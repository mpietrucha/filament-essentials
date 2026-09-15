<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\HasSelectTitleWithAvatar;
use Mpietrucha\Laravel\Essentials\Eloquent\Qualifiers\AttributeQualifier;

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
        $relationship = AttributeQualifier::relationship($attribute = $this->getAttribute());

        if ($relationship === null) {
            return $this;
        }

        $attribute = AttributeQualifier::attribute($attribute);

        return $this->query(fn (Builder $builder, array $data): Builder => $builder->whereHas(
            $relationship,
            function (Builder $builder) use ($attribute, $data): void {
                /** @phpstan-ignore method.nonObject, argument.templateType */
                $value = Collection::make($data)->flatten()->unless($isMultiple = $this->isMultiple())->first();

                if (blank($value)) {
                    return;
                }

                $builder->{$isMultiple ? 'whereIn' : 'where'}($attribute, $value);
            }
        ));
    }
}
