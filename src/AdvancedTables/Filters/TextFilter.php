<?php

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filters;

use Archilex\AdvancedTables\Filters\TextFilter as ArchilexTextFilter;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\Column;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
        public function apply(Builder $builder, array $data = []): Builder
        {
            if (! static::isListOperator($data)) {
                return parent::apply($builder, $data);
            }

            $values = static::getListValues($data);

            if ($values->isEmpty()) {
                return $builder;
            }

            return $builder->{static::isInListOperator($data) ? 'whereIn' : 'whereNotIn'}(
                $this->getQueryColumn($builder),
                $values
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
            ]);

            return $schema;
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
    }
} else {
    PackageException::missing('TextFilter');
}
