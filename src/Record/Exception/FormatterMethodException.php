<?php

namespace Mpietrucha\Filament\Essentials\Record\Exception;

use Mpietrucha\Utility\Throwable\BadMethodCallException;

class FormatterMethodException extends BadMethodCallException
{
    public function configure(string $method): string
    {
        return 'Method `%s` cannot be used as formatter';
    }
}
