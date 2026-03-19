<?php

namespace Mpietrucha\Filament\Essentials\Actions\Exception;

use Mpietrucha\Utility\Throwable\RuntimeException;

class ImportMergeException extends RuntimeException
{
    public function initialize(): void
    {
        'Unable to merge CSV files' |> $this->message(...);
    }
}
