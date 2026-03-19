<?php

namespace Mpietrucha\Filament\Essentials\Record;

use Filament\Infolists\Components\TextEntry;
use Filament\Models\Contracts\HasAvatar;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Arr;
use Mpietrucha\Support\Exception\BadMethodCallException;

/**
 * @mixin TextColumn
 * @mixin TextEntry
 */
class Adapter extends Context
{
    /**
     * @param  array<mixed>  $arguments
     */
    public function __call(string $method, array $arguments): mixed
    {
        if (StateFormatter::incompatible($method)) {
            BadMethodCallException::throw('Call to undefined method %s::%s', static::class, $method);
        }

        $value = (string) Arr::first($arguments);

        return StateFormatter::format($this->component, $method, $value, array_slice($arguments, 1));
    }

    public function get(string $attribute): mixed
    {
        $model = $this->model;

        return data_get($model, $attribute);
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
