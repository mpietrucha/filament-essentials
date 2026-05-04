<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Translations\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Mpietrucha\Filament\Essentials\Resources\Concerns\GuessesResource;

class ManageTranslations extends ManageRecords
{
    use GuessesResource;

    /**
     * @return list<Action>
     */
    protected function getHeaderActions(): array
    {
        /** @var list<Action> */
        return [
            static::getResource()::getCreateAction(),
        ];
    }
}
