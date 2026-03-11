<?php

namespace Mpietrucha\Filament\Essentials\Enums\Concerns;

use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentTimezone;
use Filament\Tables\Table;
use Mpietrucha\Laravel\Essentials\Locale\Currency;

/**
 * @phpstan-require-implements \Mpietrucha\Filament\Essentials\Enums\Contracts\InteractsWithEnumInterface
 */
trait InteractsWithLocale
{
    use \Mpietrucha\Laravel\Essentials\Enums\Concerns\InteractsWithLocale;

    public static function configure(): void
    {
        $handler = static::bootstrap(...);

        Table::configureUsing($handler);
        Schema::configureUsing($handler);
    }

    public static function bootstrap(Schema|Table $component): Schema|Table
    {
        $locale = static::current();

        if ($value = $locale->currency()) {
            $component->defaultCurrency($value);
        }

        if ($value = $locale->timezone()) {
            FilamentTimezone::set($value);
        }

        if ($value = $locale->numberLocale()) {
            $component->defaultNumberLocale($value);
        }

        if ($value = $locale->timeDisplayFormat()) {
            $component->defaultTimeDisplayFormat($value);
        }

        if ($value = $locale->dateDisplayFormat()) {
            $component->defaultDateDisplayFormat($value);
        }

        if ($value = $locale->isoTimeDisplayFormat()) {
            $component->defaultIsoTimeDisplayFormat($value);
        }

        if ($value = $locale->isoDateDisplayFormat()) {
            $component->defaultIsoDateDisplayFormat($value);
        }

        if ($value = $locale->dateTimeDisplayFormat()) {
            $component->defaultDateTimeDisplayFormat($value);
        }

        if ($value = $locale->isoDateTimeDisplayFormat()) {
            $component->defaultIsoDateTimeDisplayFormat($value);
        }

        return $component;
    }

    public function currency(): ?string
    {
        return Currency::get();
    }

    public function timezone(): ?string
    {
        return null;
    }

    public function numberLocale(): ?string
    {
        return null;
    }

    public function timeDisplayFormat(): ?string
    {
        return null;
    }

    public function dateDisplayFormat(): ?string
    {
        return null;
    }

    public function isoTimeDisplayFormat(): ?string
    {
        return null;
    }

    public function isoDateDisplayFormat(): ?string
    {
        return null;
    }

    public function dateTimeDisplayFormat(): ?string
    {
        return null;
    }

    public function isoDateTimeDisplayFormat(): ?string
    {
        return null;
    }
}
