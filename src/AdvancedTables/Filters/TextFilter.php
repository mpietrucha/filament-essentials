<?php

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filters;

use Archilex\AdvancedTables\Filters\TextFilter as ArchilexTextFilter;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\Column;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;
use Mpietrucha\Support\Exception\RuntimeException;
use Mpietrucha\Support\Str;
use Throwable;

if (class_exists(ArchilexTextFilter::class)) {
    /**
     * @phpstan-type ListCollection Collection<int, non-falsy-string>
     * @phpstan-type FormData array<string, mixed>
     * @phpstan-type EloquentBuilder Builder<Model>
     * @phpstan-type OperatorArray array<string, string>
     * @phpstan-type IndicatorArray array<string>
     */
    class TextFilter extends ArchilexTextFilter
    {
        public const string IN_LIST = 'in_list';

        public const string NOT_IN_LIST = 'not_in_list';

        /**
         * @param  EloquentBuilder  $builder
         * @param  FormData  $data
         * @return EloquentBuilder
         */
        #[\Override]
        public function applyToBaseQuery(Builder $builder, array $data = []): Builder
        {
            if (! static::isListOperator($data)) {
                return parent::applyToBaseQuery($builder, $data);
            }

            if (! static::isInListOperator($data) || ! static::isSortByList($data)) {
                return parent::applyToBaseQuery($builder, $data);
            }

            $values = static::getListValues($data);

            if ($values->isEmpty()) {
                return parent::applyToBaseQuery($builder, $data);
            }

            $column = $this->getQueryColumn($builder);

            $relationship = $this->getColumn()?->getRelationshipName(
                $builder->getModel()
            );

            static::applyListOrder($builder, $column, $values, $relationship);

            return $builder;
        }

        /**
         * @param  EloquentBuilder  $builder
         * @param  FormData  $data
         * @return EloquentBuilder
         */
        #[\Override]
        public function apply(Builder $builder, array $data = []): Builder
        {
            if (! static::isListOperator($data)) {
                return parent::apply($builder, $data);
            }

            $values = static::getListValues($data);

            if ($values->isEmpty()) {
                return $builder;
            }

            $isInListOperator = static::isInListOperator($data);

            $column = $this->getQueryColumn($builder);

            $relationship = $this->getColumn()?->getRelationshipName(
                $builder->getModel()
            );

            if ($relationship === null) {
                return $builder->{$isInListOperator ? 'whereIn' : 'whereNotIn'}(
                    $column,
                    $values,
                );
            }

            return $builder->{$isInListOperator ? 'whereHas' : 'whereDoesntHave'}(
                $relationship,
                static fn (Builder $builder): Builder => $builder->whereIn($column, $values)
            );
        }

        #[\Override]
        public function getFormSchema(): array
        {
            $schema = parent::getFormSchema();

            try {
                /** @var Grid $grid */
                $grid = $schema[0]->getDefaultChildComponents()[2]; /** @phpstan-ignore offsetAccess.nonOffsetAccessible, method.notFound */

                /** @var array<Component> $gridComponents */
                $gridComponents = $grid->getDefaultChildComponents();
            } catch (Throwable) {
                RuntimeException::throw('Unsupported filter schema implementation');
            }

            $input = Arr::first($gridComponents);

            if (! $input instanceof TextInput) {
                return $schema;
            }

            $invadedInput = invade($input);

            /** @phpstan-ignore property.notFound */
            $isInputHidden = $invadedInput->isHidden;

            $isListOperator = static fn (Get $get): bool => static::isListOperator([
                $operator = static::getOperatorAttribute() => $get($operator),
            ]);

            $input->hidden(static function (Get $get) use ($isInputHidden, $isListOperator): bool {
                if ($isListOperator($get)) {
                    return true;
                }

                return (bool) value($isInputHidden, $get);
            });

            $grid->schema([
                ...$gridComponents,
                Textarea::make(static::getListAttribute())
                    ->hiddenLabel()
                    ->visible($isListOperator)
                    ->columnSpan(
                        $invadedInput->columnSpan /** @phpstan-ignore argument.type, property.notFound */
                    ),
                Toggle::make(static::getSortByListAttribute())
                    ->default(true)
                    ->visible($isListOperator)
                    ->columnSpan(
                        $invadedInput->columnSpan /** @phpstan-ignore argument.type, property.notFound */
                    ),
            ]);

            return $schema;
        }

        #[\Override]
        protected function hasBaseQueryModificationCallback(): bool
        {
            return true;
        }

        /**
         * @return OperatorArray
         */
        protected function getOperators(): array
        {
            /** @var OperatorArray */
            return parent::getOperators() + [
                static::IN_LIST => __('filament-essentials::advanced-tables.text.in_list.option'),
                static::NOT_IN_LIST => __('filament-essentials::advanced-tables.text.not_in_list.option'),
            ];
        }

        /**
         * @param  FormData  $data
         */
        protected function formFilled(array $data): bool
        {
            if (! static::isListOperator($data)) {
                return parent::formFilled($data);
            }

            return static::getListValue($data) |>  filled(...);
        }

        /**
         * @param  FormData  $data
         * @return IndicatorArray
         */
        protected function getFilterIndicator(ArchilexTextFilter $archilexTextFilter, array $data): array
        {
            if (! static::isListOperator($data)) {
                /** @var IndicatorArray */
                return parent::getFilterIndicator($archilexTextFilter, $data);
            }

            $label = with(
                $archilexTextFilter->getName() |> $this->getTable()->getColumn(...),
                static function (?Column $column): ?string {
                    if (! $column instanceof Column) {
                        return null;
                    }

                    $label = $column->getLabel();

                    return $label instanceof Htmlable ? null : $label;
                }
            );

            if ($label === null) {
                return [];
            }

            $indicator = sprintf(
                'filament-essentials::advanced-tables.text.%s.indicator',
                static::getOperatorValue($data)
            ) |> __(...);

            return sprintf(
                '%s %s (%s)',
                $label,
                $indicator,
                static::getListValues($data)->count()
            ) |> Arr::wrap(...);
        }

        protected static function getOperatorAttribute(): string
        {
            return 'operator';
        }

        protected static function getListAttribute(): string
        {
            return 'list';
        }

        protected static function getSortByListAttribute(): string
        {
            return 'sort_by_list';
        }

        /**
         * @param  FormData  $data
         */
        protected static function getAttributeStringValue(array $data, string $attribute): string
        {
            $value = Arr::get($data, $attribute);

            if (is_string($value)) {
                return $value;
            }

            return Str::none();
        }

        /**
         * @param  FormData  $data
         */
        protected static function getOperatorValue(array $data): string
        {
            return static::getAttributeStringValue(
                $data,
                static::getOperatorAttribute()
            );
        }

        /**
         * @param  FormData  $data
         */
        protected static function getListValue(array $data): string
        {
            return static::getAttributeStringValue(
                $data,
                static::getListAttribute()
            );
        }

        /**
         * @param  FormData  $data
         */
        protected static function isInListOperator(array $data): bool
        {
            return static::getOperatorValue($data) === static::IN_LIST;
        }

        /**
         * @param  FormData  $data
         */
        protected static function isNotInListOperator(array $data): bool
        {
            return static::getOperatorValue($data) === static::NOT_IN_LIST;
        }

        /**
         * @param  FormData  $data
         */
        protected static function isListOperator(array $data): bool
        {
            if (static::isInListOperator($data)) {
                return true;
            }

            return static::isNotInListOperator($data);
        }

        /**
         * @param  FormData  $data
         * @return ListCollection
         */
        protected static function getListValues(array $data): Collection
        {
            $values = explode(
                Str::eol(), /** @phpstan-ignore argument.type */
                static::getListValue($data)
            );

            return collect($values)->map(Str::squish(...))->filter();
        }

        /**
         * @param  FormData  $data
         */
        protected static function isSortByList(array $data): bool
        {
            return (bool) Arr::get(
                $data,
                static::getSortByListAttribute(),
                true
            );
        }

        /**
         * @param  EloquentBuilder  $builder
         * @param  ListCollection  $values
         */
        protected static function applyListOrder(Builder $builder, string $column, Collection $values, ?string $relationship): void
        {
            $bindings = $values->values()->all();

            $cases = Str::space() |> $values
                ->values()
                ->map(static fn (string $value, int $index): string => sprintf('WHEN ? THEN %d', $index))
                ->implode(...);

            /** @var literal-string $expression */
            $expression = sprintf('CASE %s %s END', $column, $cases);

            $builder->reorder();

            if ($relationship === null) {
                $builder->orderByRaw($expression, $bindings);

                return;
            }

            /** @var Relation<Model, Model, *> $relation */
            $relation = $builder->getModel()->{$relationship}();

            $subquery = $relation->getRelationExistenceQuery(
                $relation->getRelated()->newQueryWithoutRelationships(),
                $builder,
                [$column],
            )->orderByRaw($expression, $bindings)->limit(1);

            $subquery->getQuery()->columns = null;
            $subquery->selectRaw($expression, $bindings);

            $builder->orderBy($subquery);
        }
    }
} else {
    PackageException::missing('TextFilter');
}
