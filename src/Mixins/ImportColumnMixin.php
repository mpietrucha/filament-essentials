<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Actions\Imports\ImportColumn;
use Illuminate\Support\Collection;
use Mpietrucha\Filament\Essentials\Enums\Contracts\EnumInterface;
use Mpietrucha\Laravel\Essentials\Locale;
use Mpietrucha\Laravel\Essentials\Locale\Enums\Contracts\LocaleInterface;
use Mpietrucha\Support\Arr;
use Mpietrucha\Support\Enum;

/**
 * @phpstan-require-extends ImportColumn
 */
trait ImportColumnMixin
{
    public function labelAndExampleHeader(string $labelAndExampleHeader): static
    {
        $this->label($labelAndExampleHeader);
        $this->exampleHeader($labelAndExampleHeader);

        return $this;
    }

    public function labeledEnum(string $enum, bool $examples = true): static
    {
        $enum = Enum::unit($enum, EnumInterface::class);

        $this->enum($enum);

        $getLabelCollection = static function () use ($enum): Collection {
            $caseCollection = $enum::collection();

            return is_string(...) |> $caseCollection->map->getLabel()->filter(...);
        };

        $castStateUsing = static function (mixed $state) use (&$castStateUsing, $getLabelCollection): mixed {
            if (is_array($state)) {
                return Arr::map($state, $castStateUsing);
            }

            if (blank($state) || ! is_string($state)) {
                return $state;
            }

            $localeEnum = Locale::enum();

            /** @phpstan-ignore return.type */
            return $localeEnum::collection()->map(static fn (LocaleInterface $locale): ?string => $localeEnum::with(
                $locale,
                /** @phpstan-ignore return.type */
                static fn (): ?string => $getLabelCollection()->search($state) ?: null
            ))->first() ?? $state;
        };

        $this->castStateUsing($castStateUsing);

        if (! $examples || $this->examples) {
            return $this;
        }

        return $getLabelCollection()->all() |> $this->examples(...);
    }
}
