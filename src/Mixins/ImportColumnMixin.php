<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Closure;
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

    public function labeledEnum(string $enum, bool $examples = true, bool $validation = true, bool $exclusive = true): static
    {
        $examples = $examples && $exclusive;
        $validation = $validation && $exclusive;

        $enum = Enum::backed($enum, EnumInterface::class);

        if ($validation) {
            $this->enum($enum);
        }

        /** @var Closure(): Collection<string, string> $getLabelCollection */
        $getLabelCollection = static fn (): Collection => $enum::collection()->mapWithKeys(static function (EnumInterface $enum): array {
            $label = $enum->getLabel();

            return is_string($label) ? [$enum->value => $label] : [];
        })->filter();

        $castStateUsing = static function (mixed $state) use (&$castStateUsing, $getLabelCollection): mixed {
            if (is_array($state)) {
                return Arr::map($state, $castStateUsing);
            }

            if (blank($state)) {
                return $state;
            }

            if (! is_string($state)) {
                return $state;
            }

            $localeEnum = Locale::enum();

            /** @phpstan-ignore return.type */
            return $localeEnum::collection()->map(static fn (LocaleInterface $locale): ?string => $localeEnum::with(
                $locale,
                static fn (): ?string => $getLabelCollection()->search(static function (string $label, string $value) use ($state): bool {
                    if (strcasecmp($state, $label) === 0) {
                        return true;
                    }

                    return strcasecmp($state, $value) === 0;
                }) ?: null,
            ))->filter()->first() ?? $state;
        };

        $this->castStateUsing($castStateUsing);

        if (! $examples || $this->examples) {
            return $this;
        }

        return $getLabelCollection()->values()->all() |> $this->examples(...);
    }
}
