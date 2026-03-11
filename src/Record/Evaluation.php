<?php

namespace Mpietrucha\Filament\Essentials\Record;

use Closure;
use Filament\Support\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Utility\Concerns\Creatable;
use Mpietrucha\Utility\Contracts\CreatableInterface;
use Mpietrucha\Utility\Forward\Concerns\Bridgeable;
use Mpietrucha\Utility\Value;

/**
 * @phpstan-type EvaluationComponent \Filament\Schemas\Components\Component|\Filament\Tables\Columns\Column
 */
abstract class Evaluation implements CreatableInterface
{
    use Bridgeable, Creatable;

    /**
     * @param  EvaluationComponent  $component
     */
    public function __construct(protected Component $component, protected Model $model)
    {
    }

    public static function replicate(self $evaluation): static
    {
        $component = $evaluation->component();

        $model = $evaluation->model();

        return static::create($component, $model);
    }

    public static function bind(Closure $handler): Closure
    {
        return /** @param EvaluationComponent $component **/ function (Component $component, Model $model) use ($handler) {
            $evaluation = static::create($component, $model);

            return Value::for($handler)->get($evaluation);
        };
    }

    /**
     * @return EvaluationComponent
     */
    protected function component(): Component
    {
        return $this->component;
    }

    protected function model(): Model
    {
        return $this->model;
    }
}
