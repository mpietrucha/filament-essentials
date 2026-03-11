<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Tabs;
use Mpietrucha\Laravel\Essentials\Locale;
use Mpietrucha\Utility\Collection;
use Mpietrucha\Utility\Enum;
use Mpietrucha\Utility\Enums\Contracts\InteractsWithEnumInterface;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 *
 * @method static|\Filament\Schemas\Components\Tabs translatable(bool|\Closure $translatable = true, ?\Closure $modifyLocalizedFieldUsing = null, MixedArray|\Closure|null $supportedLocales = null, string|\Closure|null $defaultLocale = null)
 *
 * @phpstan-require-extends \Filament\Forms\Components\Field
 */
trait FieldMixin
{
    /**
     * @param  null|MixedArray|string|\Mpietrucha\Filament\Essentials\Enums\Contracts\InteractsWithEnumInterface  $requiredLocales
     */
    public function translate(null|array|InteractsWithEnumInterface|string $requiredLocales = null): Tabs
    {
        $requiredLocales = Collection::create($requiredLocales)
            ->map(function (mixed $requiredLocale) {
                if (Type::string($requiredLocale)) {
                    return $requiredLocale;
                }

                if (Enum::compatible($requiredLocale)) {
                    return $requiredLocale->value();
                }

                return null;
            })
            ->filter();

        /** @var \Filament\Schemas\Components\Tabs */
        return $this->translatable(
            defaultLocale: $requiredLocales->first() ?? Locale::get(),
            supportedLocales: function () {
                $locale = Locale::enum();

                if (Type::null($locale)) {
                    return null;
                }

                return $locale::collection()->mapWithKeys(fn (InteractsWithEnumInterface $locale) => [
                    $locale->value() => $locale->label(),
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
