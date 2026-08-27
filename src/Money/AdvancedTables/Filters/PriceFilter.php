<?php

namespace Mpietrucha\Filament\Essentials\Money\AdvancedTables\Filters;

use Archilex\AdvancedTables\Filament\Filter as ArchilexFilter;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;
use Mpietrucha\Filament\Essentials\AdvancedTables\Filters\AdvancedFilter;
use Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Attributes\TextAttribute;
use Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Concerns\InteractsWithFilters;
use Mpietrucha\Filament\Essentials\Locale\Filters\CurrencyFilter;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Laravel\Essentials\Locale;
use Mpietrucha\Laravel\Essentials\Locale\Currency;
use Mpietrucha\Laravel\Essentials\Money\CurrencyConverter;
use Mpietrucha\Laravel\Essentials\Money\PriceAttribute;
use Throwable;

if (class_exists(ArchilexFilter::class)) {
    /**
     * @phpstan-import-type FormData from AdvancedFilter
     * @phpstan-import-type IndicatorArray from AdvancedFilter
     */
    class PriceFilter extends ArchilexFilter
    {
        use InteractsWithFilters;

        protected mixed $sourceCurrencyFilter = true;

        protected bool $hasSourceCurrencyFilter = false;

        protected ?string $locale = null;

        protected ?string $sourceCurrency = null;

        protected ?string $targetCurrency = null;

        protected function setUp(): void
        {
            parent::setUp();

            $this->schema(function (): array {
                $sourceCurrencyFilter = $this->getSourceCurrencyFilter();

                $numericFilterSchema = $this->getNumericFilter()->getFormSchema();

                if (! $sourceCurrencyFilter) {
                    return $numericFilterSchema;
                }

                /** @phpstan-ignore method.notFound, offsetAccess.nonOffsetAccessible, method.nonObject */
                $this->getLabel() |> $numericFilterSchema[0]->getDefaultChildComponents()[1]->label(...);

                return Arr::prepend(
                    $numericFilterSchema,
                    $sourceCurrencyFilter->getName() |> $sourceCurrencyFilter->getFormField()->statePath(...)
                );
            });

            $this->query(function (Builder $builder, array $data): void {
                /** @var FormData $data */
                $sourceCurrency = $this->getSourceCurrencyValue($data);

                $convertedPrice = $this->getConvertedPriceValue($data, $sourceCurrency);

                if (! $convertedPrice instanceof Money) {
                    return;
                }

                $this->getNumericFilter()->apply($builder, $this->replaceFormPriceValues(
                    $data,
                    $convertedPrice->getAmount()->toFloat(),
                    $this->getConvertedEndPriceValue($data, $sourceCurrency)?->getAmount()->toFloat(),
                ));
            });

            $this->indicateUsing(function (array $data): array {
                $numericFilter = $this->getNumericFilter();

                /** @phpstan-ignore property.notFound */
                $indicateUsing = invade($numericFilter)->indicateUsing;

                $locale = $this->getLocale();

                /** @var FormData $data */
                $sourceCurrency = $this->getSourceCurrencyValue($data);

                /** @var IndicatorArray */
                return $indicateUsing($numericFilter, $this->replaceFormPriceValues( /* @phpstan-ignore callable.nonCallable */
                    $data,
                    $this->getConvertedPriceValue($data, $sourceCurrency)?->formatToLocale($locale),
                    $this->getConvertedEndPriceValue($data, $sourceCurrency)?->formatToLocale($locale),
                ));
            });
        }

        public static function make(?string $name = null, ?string $indicator = null, ?string $relationship = null): static
        {
            $name ??= PriceAttribute::getNormalizedPrice($indicator);

            return Record::buildRelationshipAttribute($name, $relationship) |> parent::make(...);
        }

        public function withSourceCurrencyFilter(mixed $sourceCurrencyFilter): static
        {
            $this->sourceCurrencyFilter = $sourceCurrencyFilter;

            return $this;
        }

        public function withoutSourceCurrencyFilter(): static
        {
            $this->sourceCurrencyFilter = false;

            return $this;
        }

        public function withLocale(mixed $locale): static
        {
            $this->locale = Locale::enum()::build($locale)->code();

            return $this;
        }

        public function withSourceCurrency(mixed $sourceCurrency): static
        {
            $this->sourceCurrency = CurrencyConverter::currency($sourceCurrency);

            return $this;
        }

        public function withTargetCurrency(mixed $targetCurrency): static
        {
            $this->targetCurrency = CurrencyConverter::currency($targetCurrency);

            return $this;
        }

        protected function getSourceCurrencyFilter(): ?CurrencyFilter
        {
            if ($this->sourceCurrencyFilter instanceof CurrencyFilter && $this->hasSourceCurrencyFilter) {
                return $this->sourceCurrencyFilter;
            }

            if ($this->sourceCurrencyFilter === false) {
                return null;
            }

            $sourceCurrencyFilter = $this->sourceCurrencyFilter |> $this->evaluate(...);

            if (! $sourceCurrencyFilter instanceof CurrencyFilter) {
                $sourceCurrencyFilter = CurrencyFilter::make();

                $this->getSourceCurrency() |> $sourceCurrencyFilter->default(...);

                __('filament-essentials::money.filter.price_filter.currency') |> $sourceCurrencyFilter->label(...);
            }

            $this->hasSourceCurrencyFilter = true;

            $sourceCurrencyFilter->multiple(false);
            $sourceCurrencyFilter->selectablePlaceholder(false);

            return $this->sourceCurrencyFilter = $sourceCurrencyFilter;
        }

        protected function getLocale(): string
        {
            if ($locale = $this->locale) {
                return $locale;
            }

            return $this->locale = Locale::get()->code();
        }

        protected function getSourceCurrency(): string
        {
            if ($sourceCurrency = $this->sourceCurrency) {
                return $sourceCurrency;
            }

            return $this->sourceCurrency = Currency::get()->symbol();
        }

        protected function getTargetCurrency(): string
        {
            if ($targetCurrency = $this->targetCurrency) {
                return $targetCurrency;
            }

            $getDefaultTargetCurrency = sprintf(
                'getDefault%sTargetCurrency',
                $this->getName() |> Str::relationshipAttribute(...) |> Str::studly(...)
            );

            try {
                /** @phpstan-ignore method.nonObject, staticMethod.nonObject */
                $relation = $this->getTable()->getModel()::make() |> $this->getNumericFilter()->getColumn()->getRelationship(...);

                /** @phpstan-ignore method.nonObject */
                $targetCurrency = $relation->getRelated()::$getDefaultTargetCurrency() |> $this->withTargetCurrency(...);

                /** @var string */
                return $this->targetCurrency;
            } catch (Throwable) {
                return $this->targetCurrency = Currency::enum()::default()->symbol();
            }
        }

        /**
         * @param  FormData  $data
         */
        protected function getSourceCurrencyValue(array $data): string
        {
            $sourceCurrencyAttribute = $this->getSourceCurrencyFilter()?->getName();

            if ($sourceCurrencyAttribute === null) {
                return $this->getSourceCurrency();
            }

            return Arr::tryNotEmptyString($data, $sourceCurrencyAttribute) ?? $this->getSourceCurrency();
        }

        /**
         * @param  FormData  $data
         */
        protected function getConvertedPriceValue(array $data, ?string $sourceCurrency = null, ?string $priceAttribute = null): ?Money
        {
            $price = Arr::tryScalar($data, $priceAttribute ?? TextAttribute::VALUE);

            if ($price === null) {
                return null;
            }

            try {
                return CurrencyConverter::convert(
                    (string) $price,
                    $this->getTargetCurrency(),
                    $sourceCurrency ?? $this->getSourceCurrencyValue($data)
                );
            } catch (Throwable) {
                return null;
            }
        }

        /**
         * @param  FormData  $data
         */
        protected function getConvertedEndPriceValue(array $data, ?string $sourceCurrency = null): ?Money
        {
            return $this->getConvertedPriceValue($data, $sourceCurrency, TextAttribute::END);
        }

        /**
         * @param  FormData  $data
         * @return FormData
         */
        protected function replaceFormPriceValues(array $data, mixed $value, mixed $end): array
        {
            $data[TextAttribute::END] = $end;
            $data[TextAttribute::VALUE] = $value;

            return $data;
        }
    }
} else {
    PackageException::missing('PriceFilter');
}
