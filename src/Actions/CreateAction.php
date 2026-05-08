<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Actions;

use Closure;
use Filament\Actions\CreateAction as FilamentCreateAction;
use Illuminate\Database\Eloquent\Model;

class CreateAction extends FilamentCreateAction
{
    protected ?Model $parentRecord = null;

    #[\Override]
    public function record(null|array|Closure|Model|string $record): static
    {
        parent::record($record);

        if ($this->parentRecord instanceof Model) {
            return $this;
        }

        $record = $this->evaluate($record);

        $this->parentRecord = $record instanceof Model ? $record : null;

        return $this;
    }

    #[\Override]
    protected function resolveDefaultClosureDependencyForEvaluationByName(string $parameterName): array
    {
        if ($parameterName === 'parentRecord') {
            return [$this->parentRecord];
        }

        return parent::resolveDefaultClosureDependencyForEvaluationByName($parameterName);
    }
}
