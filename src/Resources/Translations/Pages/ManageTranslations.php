<?php

namespace Mpietrucha\Filament\Essentials\Resources\Translations\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Mpietrucha\Filament\Essentials\Pages\Concerns\GuessesResource;
use Mpietrucha\Filament\Essentials\Resources\Resource as FilamentResource;

class ManageTranslations extends ManageRecords
{
    use GuessesResource;

    /**
     * @return list<Action>
     */
    protected function getHeaderActions(): array
    {
        /** @var class-string<FilamentResource> $resource */
        $resource = static::getResource();

        return [
            $resource::getCreateAction(),
        ];
    }
}
