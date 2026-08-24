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
use Illuminate\Support\Str;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;
use Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Attributes\TextAttribute;
use Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Operators\TextOperator;
use Mpietrucha\Support\Exception\RuntimeException;
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
        public static function fromColumn(Column $column): static
        {
            $textFilter = $column->getName() |> static::make(...);

            $label = $column->getLabel();

            return $textFilter->label($label)->column($column);
        }

        /**
         * @param  EloquentBuilder  $builder
         * @param  FormData  $data
         * @return EloquentBuilder
         */
        public function applyToBaseQuery(Builder $builder, array $data = []): Builder
        {
            if (! $this->isInListOperator($data)) {
                return parent::applyToBaseQuery($builder, $data);
            }

            return $this->applyListOrderToBaseQuery($builder, $data);
        }

        /**
         * @param  EloquentBuilder  $builder
         * @param  FormData  $data
         * @return EloquentBuilder
         */
        public function apply(Builder $builder, array $data = []): Builder
        {
            if (! $this->isListOperator($data)) {
                return parent::apply($builder, $data);
            }

            return $this->applyListFilter($builder, $data);
        }

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
                $operator = TextAttribute::OPERATOR => $get($operator),
            ]);

            $input->hidden(static function (Get $get) use ($isInputHidden, $isListOperator): bool {
                if ($isListOperator($get)) {
                    return true;
                }

                return (bool) value($isInputHidden, $get);
            });

            $grid->schema([
                ...$gridComponents,
                Textarea::make(TextAttribute::LIST)
                    ->hiddenLabel()
                    ->visible($isListOperator)
                    ->columnSpan(
                        $invadedInput->columnSpan /** @phpstan-ignore argument.type, property.notFound */
                    ),
                Toggle::make(TextAttribute::SORT_BY_LIST)
                    ->label(__('filament-essentials::advanced-tables.text.sort_by_list'))
                    ->default(true)
                    ->visible($isListOperator)
                    ->columnSpan(
                        $invadedInput->columnSpan /** @phpstan-ignore argument.type, property.notFound */
                    ),
            ]);

            return $schema;
        }

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
                TextOperator::IN_LIST => __('filament-essentials::advanced-tables.text.in_list.option'),
                TextOperator::NOT_IN_LIST => __('filament-essentials::advanced-tables.text.not_in_list.option'),
            ];
        }

        /**
         * @param  FormData  $data
         */
        protected function formFilled(array $data): bool
        {
            if (! $this->isListOperator($data)) {
                return parent::formFilled($data);
            }

            return $this->getListValue($data) |> filled(...);
        }

        /**
         * @param  FormData  $data
         * @return IndicatorArray
         */
        protected function getFilterIndicator(ArchilexTextFilter $archilexTextFilter, array $data): array
        {
            if (! $this->isListOperator($data)) {
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

        /**
         * @param  EloquentBuilder  $builder
         */
        protected function getRelationshipName(Builder $builder): ?string
        {
            $column = $this->getColumn();

            if (! $column instanceof Column) {
                return null;
            }

            return $builder->getModel() |> $column->getRelationshipName(...);
        }

        /**
         * @param  FormData  $data
         */
        protected function getOperatorValue(array $data): string
        {
            return Arr::string($data, TextAttribute::OPERATOR);
        }

        /**
         * @param  FormData  $data
         */
        protected function getListValue(array $data): string
        {
            return Arr::string($data, TextAttribute::LIST);
        }

        /**
         * @param  FormData  $data
         */
        protected function isInListOperator(array $data): bool
        {
            return $this->getOperatorValue($data) === TextOperator::IN_LIST;
        }

        /**
         * @param  FormData  $data
         */
        protected function isNotInListOperator(array $data): bool
        {
            return $this->getOperatorValue($data) === TextOperator::NOT_IN_LIST;
        }

        /**
         * @param  FormData  $data
         */
        protected function isListOperator(array $data): bool
        {
            if ($this->isInListOperator($data)) {
                return true;
            }

            return $this->isNotInListOperator($data);
        }

        /**
         * @param  FormData  $data
         * @return ListCollection
         */
        protected function getListValues(array $data): Collection
        {
            $values = explode(
                Str::eol(), /** @phpstan-ignore argument.type */
                $this->getListValue($data)
            );

            return collect($values)->map(Str::squish(...))->filter();
        }

        /**
         * @param  FormData  $data
         */
        protected function isSortedByList(array $data): bool
        {
            return (bool) Arr::get($data, TextAttribute::SORT_BY_LIST, true);
        }

        /**
         * @param  EloquentBuilder  $builder
         * @param  FormData  $data
         * @return EloquentBuilder
         */
        protected function applyListOrderToBaseQuery(Builder $builder, array $data): Builder
        {
            if (! $this->isSortedByList($data)) {
                return $builder;
            }

            $values = $this->getListValues($data);

            if ($values->isEmpty()) {
                return $builder;
            }

            $bindings = $values->values()->all();

            $cases = Str::space() |> $values
                ->values()
                ->map(static fn (string $value, int $index): string => sprintf('WHEN ? THEN %d', $index))
                ->implode(...);

            $column = $this->getQueryColumn($builder);

            /** @var literal-string $expression */
            $expression = sprintf('CASE %s %s END', $column, $cases);

            $builder->reorder();

            $relationship = $this->getRelationshipName($builder);

            if ($relationship === null) {
                $builder->orderByRaw($expression, $bindings);

                return $builder;
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

            return $builder->orderBy($subquery);
        }

        /**
         * @param  EloquentBuilder  $builder
         * @param  FormData  $data
         * @return EloquentBuilder
         */
        protected function applyListFilter(Builder $builder, array $data): Builder
        {
            $values = $this->getListValues($data);

            if ($values->isEmpty()) {
                return $builder;
            }

            $isInListOperator = $this->isInListOperator($data);

            $column = $this->getQueryColumn($builder);
            $relationship = $this->getRelationshipName($builder);

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
    }
} else {
    PackageException::missing('TextFilter');
}
