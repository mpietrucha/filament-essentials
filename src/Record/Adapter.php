<?php

namespace Mpietrucha\Filament\Essentials\Record;

use Filament\Models\Contracts\HasAvatar;
use Mpietrucha\Filament\Essentials\Record\Exception\FormatterMethodException;
use Mpietrucha\Utility\Arr;
use Mpietrucha\Utility\Data;
use Mpietrucha\Utility\Normalizer;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 *
 * @mixin \Filament\Tables\Columns\TextColumn
 * @mixin \Filament\Infolists\Components\TextEntry
 */
class Adapter extends Evaluation
{
    /**
     * @param  MixedArray  $arguments
     */
    public function __call(string $method, array $arguments): mixed
    {
        Formatter::incompatible($method) && FormatterMethodException::for($method)->throw();

        $value = Arr::first($arguments) |> Normalizer::string(...) |> $this->get(...);

        $component = $this->component();

        return Formatter::format($component, $method, $value, Arr::skip($arguments, 1));
    }

    public function get(string $attribute): mixed
    {
        $model = $this->model();

        return Data::get($model, $attribute);
    }

    public function avatar(?string $attribute = null): ?string
    {
        if (Type::string($attribute)) {
            return $this->get($attribute);
        }

        $model = $this->model();

        return $model instanceof HasAvatar ? $model->getFilamentAvatarUrl() : null;
    }
}
