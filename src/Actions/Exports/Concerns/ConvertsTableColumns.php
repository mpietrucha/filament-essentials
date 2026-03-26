<?php

namespace Mpietrucha\Filament\Essentials\Actions\Exports\Concerns;

use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Support\Htmlable;

/**
 * @phpstan-require-extends Exporter
 */
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

    /**
     * @param  array<TextColumn>  $textColumns
     * @return array<ExportColumn>
     */
    protected static function convertTextColumns(array $textColumns): array
    {
        $exportColumns = static::convertTextColumn(...) |> collect($textColumns)->map(...);

        return $exportColumns->all();
    }
}
