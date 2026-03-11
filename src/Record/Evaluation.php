<?php

namespace Mpietrucha\Filament\Essentials\Record;

use Closure;
use Filament\Actions\Action;
use Filament\Support\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Record\Exception\EvaluationBindException;
use Mpietrucha\Filament\Essentials\Record\Exception\EvaluationBuildException;
use Mpietrucha\Utility\Concerns\Creatable;
use Mpietrucha\Utility\Contracts\CreatableInterface;
use Mpietrucha\Utility\Forward\Concerns\Bridgeable;
use Mpietrucha\Utility\Type;
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

    /**
     * @param  EvaluationComponent  $component
     */
    public static function build(Component $component): static
    {
        $model = $component->getRecord();

        if ($model instanceof Model) {
            return static::create($component, $model);
        }

        EvaluationBuildException::create()->throw();
    }

    public static function replicate(self $evaluation): static
    {
        $component = $evaluation->component();

        $model = $evaluation->model();

        return static::create($component, $model);
    }

    public static function bind(Closure $handler): Closure
    {
        return /** @param null|EvaluationComponent $component **/ function (?Component $component, ?Action $action, Model $record) use ($handler) {
            if (Type::null($component)) {
                $component = $action ?? EvaluationBindException::create()->throw();
            }

            return static::create($component, $record) |> Value::for($handler)->get(...);
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
