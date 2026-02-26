<?php

namespace App\Filament\Imports;

use League\Csv\Bom;
use League\Csv\Reader;
use Mpietrucha\Utility\Type;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 */
class ImportAction extends \Filament\Actions\ImportAction
{
    /**
     * @param  null|\League\Csv\Reader<MixedArray>  $reader
     */
    public function getCsvDelimiter(?Reader $reader = null): ?string
    {
        $delimiter = parent::getCsvDelimiter($reader);

        if (Type::null($delimiter)) {
            return null;
        }

        Type::not()->null($reader) && $reader->setDelimiter($delimiter);

        return $delimiter;
    }

    protected function detectCsvEncoding(mixed $resource): ?string
    {
        $bom = Bom::tryFromSequence($resource);

        if (Type::null($bom)) {
            return parent::detectCsvEncoding($resource);
        }

        return $bom->encoding();
    }
}
