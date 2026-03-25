<?php

namespace Mpietrucha\Filament\Essentials\Record;

use Closure;
use Filament\Actions\Action;
use Filament\Support\Components\Component;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Record;
use Mpietrucha\Support\Concerns\Makeable;
use Mpietrucha\Support\Exception\RuntimeException;
use Mpietrucha\Support\Forward\Concerns\Forwardable;

/**
 * @phpstan-import-type RecordComponent from Record
 *
 * @implements Arrayable<int, mixed>
 *
 * @internal
 */
abstract class Context implements Arrayable
{
    use Forwardable;
    use Makeable;

    /**
     * @param  RecordComponent  $component
     */
    public function __construct(public readonly Component $component, public readonly Model $model)
    {
    }

    /**
     * @param  RecordComponent  $component
     */
    public static function build(Component $component): static
    {
        $model = $component->getRecord();

        if (! $model instanceof Model) {
            RuntimeException::throw('Component has no record attached');
        }

        return static::make($component, $model);
    }

    public static function pipe(Closure $handler): Closure
    {
        return /** @param null|RecordComponent $component **/ static function (?Component $component, ?Action $action, Model $record) use ($handler): mixed {
            return static::make(
                $component ?? $action ?? RuntimeException::throw('A component or action must be avaliable in this evaluation'),
                $record
            ) |> $handler;
        };
    }

    /**
     * @return array{RecordComponent, Model}
     */
    public function toArray(): array
    {
        return [
            $this->component,
            $this->model,
        ];
    }
}
