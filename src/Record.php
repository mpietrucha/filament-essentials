<?php

namespace Mpietrucha\Filament\Essentials;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Utility\Data;

abstract class Record
{
    public static function avatar(Model $record, ?string $attribute = null): ?string
    {
        if ($record instanceof HasAvatar) {
            return $record->getFilamentAvatarUrl();
        }

        return Data::get($record, $attribute);
    }
}
