<?php

namespace Mpietrucha\Filament\Essentials\Record;

use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Models\Contracts\HasAvatar;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Arr;
use Mpietrucha\Support\Exception\BadMethodCallException;
use Mpietrucha\Support\Exception\InvalidArgumentException;
use Mpietrucha\Support\Instance;
use Mpietrucha\Support\Str;
use Stringable;

/**
 * @mixin TextColumn
 * @mixin TextEntry
 *
 * @internal
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
