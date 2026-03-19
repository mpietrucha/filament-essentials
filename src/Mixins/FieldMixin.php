<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Tabs;
use Illuminate\Support\Collection;
use Mpietrucha\Laravel\Essentials\Enums\Contracts\LocaleInterface;
use Mpietrucha\Laravel\Essentials\Locale;
use Mpietrucha\Support\Str;

/**
 * @method static|\Filament\Schemas\Components\Tabs translatable(bool|\Closure $translatable = true, ?\Closure $modifyLocalizedFieldUsing = null, MixedArray|\Closure|null $supportedLocales = null, string|\Closure|null $defaultLocale = null)
 *
 * @phpstan-require-extends Field
 */
trait FieldMixin
{
    /**
     * @param  null|array<string|LocaleInterface>|string|LocaleInterface  $requiredLocales
     */
    public function translate(null|array|LocaleInterface|string $requiredLocales = null, null|LocaleInterface|string $defaultLocale = null): Tabs
    {
        /** @phpstan-ignore argument.type */
        $requiredLocales = Collection::wrap($requiredLocales)->map(static function (mixed $requiredLocale): string {
            if ($requiredLocale instanceof LocaleInterface) {
                return $requiredLocale->code();
            }

            return $requiredLocale;
        })->filter();

        /** @var Tabs */
        return $this->translatable(
            defaultLocale: static function () use ($defaultLocale, $requiredLocales): string {
                if ($defaultLocale instanceof LocaleInterface) {
                    return $defaultLocale->code();
                }

                return $defaultLocale ?? $requiredLocales->first() ?? Locale::get()->code();
            },
            supportedLocales: static function (): array {
                return Locale::enum()::collection()->mapWithKeys(static fn (LocaleInterface $locale): array => [
                    $locale->code() => $locale->code() |> Str::upper(...),
                ])->all();
            },
            modifyLocalizedFieldUsing: function (Field $field, string $locale) use ($requiredLocales) {
                if ($requiredLocales->isEmpty()) {
                    return;
                }

                $required = $this->isRequired;

                return $field->required(static fn (): bool => match (true) {
                    $field->evaluate($required) => $requiredLocales->contains($locale),
                    default => false
                });
            }
        );
    }
}
