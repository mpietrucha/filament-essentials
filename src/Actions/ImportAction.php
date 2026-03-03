<?php

namespace Mpietrucha\Filament\Essentials\Actions;

use League\Csv\Bom;
use League\Csv\Reader;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 *
 * @phpstan-type CsvReader \League\Csv\Reader<MixedArray>
 */
class ImportAction extends \Filament\Actions\ImportAction
{
    /**
     * @param  null|CsvReader  $reader
     */
    public function getCsvDelimiter(?Reader $reader = null): ?string
    {
        $delimiter = parent::getCsvDelimiter($reader);

        if (Type::null($delimiter)) {
            return null;
        }

        Type::not()->null($reader) && $this->synchronizeCsvDelimiter($reader, $delimiter);

        return $delimiter;
    }

    /**
     * @param  resource  $resource
     */
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

        if (Type::null($bom)) {
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

        if (Type::null($delimiter)) {
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
