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
        $requiredLocales = Collection::wrap($requiredLocales)->map(function (mixed $requiredLocale) {
            if (is_string($requiredLocale)) {
                return $requiredLocale;
            }

            if ($requiredLocale instanceof LocaleInterface) {
                return $requiredLocale->value;
            }
        })->filter();

        /** @var Tabs */
        return $this->translatable(
            defaultLocale: function () use ($defaultLocale, $requiredLocales) {
                if ($defaultLocale instanceof LocaleInterface) {
                    return $defaultLocale->value;
                }

                return $defaultLocale ?? $requiredLocales->first() ?? Locale::get();
            },
            supportedLocales: function () {
                return Locale::enum()::collection()->mapWithKeys(fn (LocaleInterface $locale) => [
                    $locale->value => $locale->value |> Str::upper(...),
                ])->all();
            },
            modifyLocalizedFieldUsing: function (Field $field, string $locale) use ($requiredLocales) {
                if ($requiredLocales->isEmpty()) {
                    return;
                }

                $required = $this->isRequired;

                return $field->required(fn () => match (true) {
                    $field->evaluate($required) => $requiredLocales->contains($locale),
                    default => false
                });
            }
        );
    }
}
