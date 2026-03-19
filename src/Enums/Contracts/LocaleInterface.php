<?php

namespace Mpietrucha\Filament\Essentials\Enums\Contracts;

use Filament\Schemas\Schema;
use Filament\Tables\Table;

interface LocaleInterface extends \Mpietrucha\Laravel\Essentials\Enums\Contracts\LocaleInterface, EnumInterface
{
    public static function configure(): void;

    public static function bootstrap(Schema|Table $component): Schema|Table;

    public function currency(): ?string;

    public function timezone(): ?string;

    public function numberLocale(): ?string;

    public function timeDisplayFormat(): ?string;

    public function dateDisplayFormat(): ?string;

    public function isoTimeDisplayFormat(): ?string;

    public function isoDateDisplayFormat(): ?string;

    public function dateTimeDisplayFormat(): ?string;

    public function isoDateTimeDisplayFormat(): ?string;
}
