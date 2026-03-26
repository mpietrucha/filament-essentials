<?php

namespace Mpietrucha\Filament\Essentials\Actions\Exports\Concerns;

use Filament\Actions\Exports\ExportColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Support\Htmlable;

trait ConvertsTableColumns
{
    protected static function convertTextColumn(TextColumn $textColumn): ExportColumn
    {
        $name = $textColumn->getName();

        return ExportColumn::make($name)->label(static function () use ($textColumn): string {
            $label = $textColumn->getLabel();

            if (! $label instanceof Htmlable) {
                return $label;
            }

            return $label->toHtml() |> strip_tags(...);
        });
    }
}
