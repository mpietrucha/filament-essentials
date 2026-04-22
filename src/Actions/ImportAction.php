<?php

namespace Mpietrucha\Filament\Essentials\Actions;

use Filament\Actions\ImportAction as FilamentImportAction;
use League\Csv\Bom;
use League\Csv\Reader;

/**
 * @phpstan-type CsvReader \League\Csv\Reader<array<mixed>>
 */
class ImportAction extends FilamentImportAction
{
    /**
     * @param  null|CsvReader  $reader
     */
    #[\Override]
    public function getCsvDelimiter(?Reader $reader = null): ?string
    {
        $delimiter = parent::getCsvDelimiter($reader);

        if ($delimiter === null) {
            return null;
        }

        if ($reader instanceof Reader) {
            $this->synchronizeCsvDelimiter($reader, $delimiter);
        }

        return $delimiter;
    }

    /**
     * @param  resource  $resource
     */
    #[\Override]
    protected function detectCsvEncoding(mixed $resource): ?string
    {
        return $this->detectCsvBomEncoding($resource) ?? parent::detectCsvEncoding($resource);
    }

    /**
     * @param  resource  $resource
     */
    protected function detectCsvBomEncoding(mixed $resource): ?string
    {
        $bom = Bom::tryFromSequence($resource);

        if (! $bom instanceof Bom) {
            return null;
        }

        return $bom->encoding();
    }

    /**
     * @param  CsvReader  $reader
     */
    protected function synchronizeCsvDelimiter(Reader $reader, ?string $delimiter = null): void
    {
        $delimiter ??= $this->getCsvDelimiter($reader);

        if ($delimiter === null) {
            return;
        }

        $reader->setDelimiter($delimiter);
    }

    /**
     * @param  CsvReader  $reader
     */
    protected function synchronizeHeaderOffset(Reader $reader, ?int $offset = null): void
    {
        $offset ??= $this->getHeaderOffset() ?? 0;

        $reader->setHeaderOffset($offset);
    }
}
