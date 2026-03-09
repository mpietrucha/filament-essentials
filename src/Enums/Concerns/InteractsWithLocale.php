<?php

namespace Mpietrucha\Filament\Essentials\Enums\Concerns;

use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentTimezone;
use Filament\Tables\Table;

/**
 * @phpstan-require-implements \BackedEnum
 */
trait InteractsWithLocale
{
    public static function configure(): void
    {
        $locale = app()->getLocale() |> static::from(...);

        $handler = $locale->bootstrap(...);

        Table::configureUsing($handler);
        Schema::configureUsing($handler);
    }

    public function bootstrap(Schema|Table $component): Schema|Table
    {
        if ($value = $this->currency()) {
            $component->defaultCurrency($value);
        }

        if ($value = $this->timezone()) {
            FilamentTimezone::set($value);
        }

        if ($value = $this->numberLocale()) {
            $component->defaultNumberLocale($value);
        }

        if ($value = $this->timeDisplayFormat()) {
            $component->defaultTimeDisplayFormat($value);
        }

        if ($value = $this->dateDisplayFormat()) {
            $component->defaultDateDisplayFormat($value);
        }

        if ($value = $this->isoTimeDisplayFormat()) {
            $component->defaultIsoTimeDisplayFormat($value);
        }

        if ($value = $this->isoDateDisplayFormat()) {
            $component->defaultIsoDateDisplayFormat($value);
        }

        if ($value = $this->dateTimeDisplayFormat()) {
            $component->defaultDateTimeDisplayFormat($value);
        }

        if ($value = $this->isoDateTimeDisplayFormat()) {
            $component->defaultIsoDateTimeDisplayFormat($value);
        }

        return $component;
    }

    public function currency(): ?string
    {
        return null;
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
