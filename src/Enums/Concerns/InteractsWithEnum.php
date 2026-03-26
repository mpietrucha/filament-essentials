<?php

namespace Mpietrucha\Filament\Essentials\Enums\Concerns;

use BackedEnum;
use Mpietrucha\Filament\Essentials\Enums\Contracts\EnumInterface;
use Mpietrucha\Laravel\Essentials\Locale;
use Mpietrucha\Support\Str;

/**
 * @phpstan-require-implements EnumInterface
 */
trait InteractsWithEnum
{
    use \Mpietrucha\Support\Enums\Concerns\InteractsWithEnum;

    /**
     * @return class-string<static>
     */
    public static function options(): string
    {
        return static::class;
    }

    public function getLabel(): string
    {
        /** @var string $value */
        $value = match (true) {
            $this instanceof BackedEnum => $this->value,
            default => $this->name
        };

        if ($translated = static::getTranslatedLabelValue($value)) {
            return $translated;
        }

        if (Str::upper($value) === $value) {
            return $value;
        }

        $headline = Str::headline($value);

        if (Str::startsWith(Locale::get()->code(), 'en')) {
            return $headline;
        }

        return Str::lower($headline) |> Str::ucfirst(...);
    }

    protected static function getTranslatedLabelValue(string $value): ?string
    {
        $prefix = 'static::TRANSLATION_PREFIX';

        if (! defined($prefix)) {
            return null;
        }

        $prefix = constant($prefix);

        if (! is_string($prefix)) {
            return null;
        }

        return __(sprintf('%s.%s', $prefix, $value));
    }
}
