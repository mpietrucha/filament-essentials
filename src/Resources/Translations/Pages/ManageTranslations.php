<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Translations\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Mpietrucha\Filament\Essentials\Plugins\TranslationsPlugin;
use Mpietrucha\Filament\Essentials\Resources\Translations\TranslationResource;

class ManageTranslations extends ManageRecords
{
    /**
     * @return class-string<TranslationResource>
     */
    #[\Override]
    public static function getResource(): string
    {
        /** @var class-string<TranslationResource> */
        return TranslationsPlugin::get()->getResource();
    }

    /**
     * @return list<Action>
     */
    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            static::getCreateAction(),
        ];
    }
}
