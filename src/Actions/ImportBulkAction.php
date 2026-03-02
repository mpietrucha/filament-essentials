<?php

namespace Mpietrucha\Filament\Essentials\Actions;

use Filament\Schemas\Schema;
use Mpietrucha\Filament\Essentials\Actions\Imports\Merger;
use Mpietrucha\Utility\Arr;
use Mpietrucha\Utility\Type;

class ImportBulkAction extends ImportAction
{
    public function getSchema(Schema $schema): ?Schema
    {
        $schema = parent::getSchema($schema);

        if (Type::null($schema)) {
            return null;
        }

        $upload = $schema->getComponents() |> Arr::first(...);

        $upload->multiple();

        __('filament-essentials::upload.bulk_action.placeholder') |> $upload->placeholder(...);

        /** @phpstan-ignore-next-line property.notFound */
        $handler = invade($upload)->afterStateUpdated |> Arr::first(...);

        $upload->afterStateUpdated(null);

        Merger::build($this, $handler) |> $upload->afterStateUpdated(...);

        return $schema;
    }
}
