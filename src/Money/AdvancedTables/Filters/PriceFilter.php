<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Money\AdvancedTables\Filters;

use Archilex\AdvancedTables\Filament\Filter as ArchilexFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;
use Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Concerns\InteractsWithFilters;
use Mpietrucha\Filament\Essentials\Locale\AdvancedTables\Filament\CurrencyFilter;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Laravel\Essentials\Locale\Currency;
use Mpietrucha\Laravel\Essentials\Money\PriceAttribute;

if (class_exists(ArchilexFilter::class)) {
    class PriceFilter extends ArchilexFilter
    {
        use InteractsWithFilters;

        protected bool|Closure $withCurrency = false;

        protected ?CurrencyFilter $currencyFilter = null;

        protected function setUp(): void
        {
            parent::setUp();

            $this->schema(function (): array {
                $numericFilterSchema = $this->getNumericFilter()->getFormSchema();

                if (! $this->getCurrencyFilter()) {
                    return $numericFilterSchema;
                }

                /** @phpstan-ignore method.notFound, offsetAccess.nonOffsetAccessible, method.nonObject */
                $numericFilterSchema[0]->getDefaultChildComponents()[1]->hiddenLabel();

                return Arr::prepend($numericFilterSchema, $this->getCurrencyFilter()->getFormField());
            });

            $this->query(function (Builder $builder, array $data): void {

            });

            $this->indicateUsing(function (array $data): array {
                return [];
            });
        }

        public static function make(?string $name = null, ?string $indicator = null, ?string $relationship = null): static
        {
            if ($name === null) {
                $name = PriceAttribute::getNormalizedPrice($indicator);
            }

            return Record::buildRelationshipAttribute($name, $relationship) |> parent::make(...);
        }

        public function withCurrency(?Closure $withCurrency = null): static
        {
            $this->withCurrency = $withCurrency ?? true;

            return $this;
        }

        protected function getCurrencyFilter(): ?CurrencyFilter
        {
            if ($this->currencyFilter instanceof CurrencyFilter) {
                return $this->currencyFilter;
            }

            if ($this->withCurrency === false) {
                return null;
            }

            $defaultCurrencyFilter = CurrencyFilter::make(
                PriceAttribute::getCurrency(),
                $relationship = $this->getName() |> Str::relationshipName(...)
            );

            __('filament-essentials::money.filter.text.price_filter.currency') |> $defaultCurrencyFilter->label(...);

            $maybeCurrencyFilter = value($this->withCurrency, $defaultCurrencyFilter, $relationship);

            $currencyFilter = match (true) {
                $maybeCurrencyFilter instanceof CurrencyFilter => $maybeCurrencyFilter,
                default => $defaultCurrencyFilter
            };

            $currencyFilter->multiple(false);

            Currency::enum()::default()->value |> $currencyFilter->default(...);

            return $this->currencyFilter = $currencyFilter;
        }
    }
} else {
    PackageException::missing('PriceFilter');
}
