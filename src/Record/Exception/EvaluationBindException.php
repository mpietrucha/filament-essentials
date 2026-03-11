<?php

namespace Mpietrucha\Filament\Essentials\Record\Exception;

use Mpietrucha\Utility\Throwable\RuntimeException;

class EvaluationBindException extends RuntimeException
{
    public function initialize(): void
    {
        'Evaluation cannot be bound for this instance' |> $this->message(...);
    }
}
