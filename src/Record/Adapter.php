<?php

namespace Mpietrucha\Filament\Essentials\Record;

use BackedEnum;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Arr;
use Mpietrucha\Support\Exception\BadMethodCallException;
use Mpietrucha\Support\Exception\InvalidArgumentException;
use Mpietrucha\Support\Instance;
use Mpietrucha\Support\Str;
use Stringable;

/**
 * @internal
 *
 * @method string date(string $attribute, ?string $format = null, ?string $timezone = null)
 * @method string dateTime(string $attribute, ?string $format = null, ?string $timezone = null)
 * @method string isoDate(string $attribute, ?string $format = null, ?string $timezone = null)
 * @method string isoDateTime(string $attribute, ?string $format = null, ?string $timezone = null)
 * @method string isoTime(string $attribute, ?string $format = null, ?string $timezone = null)
 * @method string since(string $attribute, ?string $timezone = null)
 * @method string money(string $attribute, string|BackedEnum|null $currency = null, int $divideBy = 0, string|BackedEnum|null $locale = null, ?int $decimalPlaces = null)
 * @method string numeric(string $attribute, ?int $decimalPlaces = null, ?string $decimalSeparator = null, ?string $thousandsSeparator = null, ?int $maxDecimalPlaces = null, string|BackedEnum|null $locale = null)
 * @method string time(string $attribute, ?string $format = null, ?string $timezone = null)
 */
class Adapter extends Context
{
    /**
     * @param  array<mixed>  $arguments
     */
    public function __call(string $method, array $arguments): string
    {
        if (StateFormatter::incompatible($method)) {
            BadMethodCallException::throw('Call to undefined method %s::%s', static::class, $method);
        }

        $state = Arr::string($arguments, 0) |> $this->get(...);

        return StateFormatter::format($this->component, $method, $state, array_slice($arguments, 1));
    }

    public function get(string $attribute): string
    {
        $value = data_get($model = $this->model, $attribute);

        if ($value === null) {
            return Str::none();
        }

        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        if ($value instanceof Stringable) {
            $value = (string) $value;
        }

        if (! is_scalar($value)) {
            InvalidArgumentException::throw('%s::$%s must be a int|float|string|bool', Instance::namespace($model), $attribute);
        }

        return (string) $value;
    }

    public function avatar(?string $attribute = null): ?string
    {
        if (is_string($attribute)) {
            return $this->get($attribute);
        }

        $model = $this->model;

        return $model instanceof HasAvatar ? $model->getFilamentAvatarUrl() : null;
    }
}
