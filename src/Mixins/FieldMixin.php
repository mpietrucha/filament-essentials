<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 *
 * @method static|\Filament\Schemas\Components\Tabs translatable(bool|\Closure $translatable = true, ?\Closure $modifyLocalizedFieldUsing = null, MixedArray|\Closure|null $supportedLocales = null, string|\Closure|null $defaultLocale = null)
 *
 * @phpstan-require-extends \Filament\Forms\Components\Field
 */
trait FieldMixin
{
    public function translate(): Component
    {
        return $this->translatable(
            defaultLocale: config('app.fallback_locale'),
            modifyLocalizedFieldUsing: function (Field $field, string $locale) {
                $required = (fn () => $this->isRequired)->call($field);

                $field->required(function () use ($field, $required, $locale) {
                    if ($field->evaluate($required)) {
                        return true;
                    }

                    return config('app.fallback_locale') === $locale;
                });
            }
        );
    }
}
