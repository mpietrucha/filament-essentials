<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Tabs;
use Mpietrucha\Laravel\Essentials\Locale;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 *
 * @method static|\Filament\Schemas\Components\Tabs translatable(bool|\Closure $translatable = true, ?\Closure $modifyLocalizedFieldUsing = null, MixedArray|\Closure|null $supportedLocales = null, string|\Closure|null $defaultLocale = null)
 *
 * @phpstan-require-extends \Filament\Forms\Components\Field
 */
trait FieldMixin
{
    public function translate(bool $all = false): Tabs
    {
        $defaultLocale = config('filament-essentials.field.translate.locale') ?? Locale::get();

        /** @var \Filament\Schemas\Components\Tabs */
        return $this->translatable(
            defaultLocale: $defaultLocale,
            modifyLocalizedFieldUsing: function (Field $field, string $locale) use ($all, $defaultLocale) {
                if ($all) {
                    return $field->required();
                }

                $required = $this->isRequired;

                return $field->required(fn () => match (true) {
                    $field->evaluate($required) => $defaultLocale === $locale,
                    default => false
                });
            }
        );
    }

    public function translateAllLocales(): Tabs
    {
        return $this->translate(true);
    }
}
