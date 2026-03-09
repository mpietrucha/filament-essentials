<?php

namespace Mpietrucha\Filament\Essentials\Record;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Record\Concerns\InteractsWithComponent;
use Mpietrucha\Filament\Essentials\Record\Exception\StateMethodException;
use Mpietrucha\Utility\Arr;
use Mpietrucha\Utility\Concerns\Creatable;
use Mpietrucha\Utility\Contracts\CreatableInterface;
use Mpietrucha\Utility\Data;
use Mpietrucha\Utility\Forward\Concerns\Bridgeable;
use Mpietrucha\Utility\Normalizer;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 */
class Adapter implements CreatableInterface
{
    use Bridgeable, Creatable, InteractsWithComponent;

    /**
     * @param  MixedArray  $arguments
     */
    public function __call(string $method, array $arguments): mixed
    {
        State::incompatible($method) && StateMethodException::for($method)->throw();

        $value = Arr::first($arguments) |> Normalizer::string(...) |> $this->get(...);

        $component = $this->component();

        return State::format($component, $method, $value, Arr::skip($arguments, 1));
    }

    public function model(): Model
    {
        /** @var \Illuminate\Database\Eloquent\Model */
        return $this->component()->getRecord();
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
