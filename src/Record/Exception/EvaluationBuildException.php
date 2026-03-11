<?php

namespace Mpietrucha\Filament\Essentials\Record\Exception;

use Mpietrucha\Utility\Throwable\RuntimeException;

class EvaluationBuildException extends RuntimeException
{
    public function initialize(): void
    {
        'Evaluation cannot be build from component without attached record' |> $this->message(...);
    }
}
