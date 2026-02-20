<?php

namespace Mpietrucha\Filament\Essentials\Record;

use Filament\Models\Contracts\HasAvatar;
use Filament\Support\Concerns\Macroable;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Utility\Concerns\Creatable;
use Mpietrucha\Utility\Contracts\CreatableInterface;
use Mpietrucha\Utility\Data;
use Mpietrucha\Utility\Type;

class Context implements CreatableInterface
{
    use Creatable, Macroable;

    public function __construct(protected Model $record)
    {
    }

    public function get(string $attribute): mixed
    {
        return Data::get($this->record(), $attribute);
    }

    public function avatar(?string $attribute = null): ?string
    {
        if (Type::string($attribute)) {
            return $this->get($attribute);
        }

        $record = $this->record();

        return $record instanceof HasAvatar ? $record->getFilamentAvatarUrl() : null;
    }

    protected function record(): Model
    {
        return $this->record;
    }
}
