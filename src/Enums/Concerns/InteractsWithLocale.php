<?php

namespace Mpietrucha\Filament\Essentials\Enums\Concerns;

use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentTimezone;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Mpietrucha\Filament\Essentials\Enums\Contracts\LocaleInterface;
use Mpietrucha\Filament\Essentials\Locale\IntlDatePatternGenerator;
use Mpietrucha\Laravel\Essentials\Locale\Currency;

/**
 * @phpstan-require-implements LocaleInterface
 */
trait InteractsWithLocale
{
    use InteractsWithEnum;
    use \Mpietrucha\Laravel\Essentials\Enums\Concerns\InteractsWithLocale;

    public static function configure(): void
    {
        $handler = static::bootstrap(...);

        Table::configureUsing($handler);
        Schema::configureUsing($handler);
    }

    public static function bootstrap(Schema|Table $component): Schema|Table
    {
        $locale = static::get();

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
        return Currency::get()->symbol();
    }

    public function timezone(): ?string
    {
        return null;
    }

    public function numberLocale(): ?string
    {
        return $this->code();
    }

    public function timeDisplayFormat(): ?string
    {
        return $this->getIntlDatePatternGenerator()->toPHPDateFormat('Hm');
    }

    public function dateDisplayFormat(): ?string
    {
        return $this->getIntlDatePatternGenerator()->toPHPDateFormat('yMd');
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
        return $this->getIntlDatePatternGenerator()->toPHPDateFormat('yMdHm');
    }

    public function isoDateTimeDisplayFormat(): ?string
    {
        return null;
    }

    protected function getIntlDatePatternGenerator(): IntlDatePatternGenerator
    {
        /** @var Collection<int, IntlDatePatternGenerator> */
        static $generators = collect();

        $code = $this->code();

        /** @var IntlDatePatternGenerator */
        return $generators->getOrPut($code, static fn (): IntlDatePatternGenerator => IntlDatePatternGenerator::make($code));
    }
}
