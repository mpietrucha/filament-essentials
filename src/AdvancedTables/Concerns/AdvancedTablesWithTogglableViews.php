<?php

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Concerns;

use Archilex\AdvancedTables\AdvancedTables;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;

if (trait_exists(AdvancedTables::class)) {
    trait AdvancedTablesWithTogglableViews
    {
        use AdvancedTables {
            loadPresetView as forceLoadPresetView;
        }

        /**
         * @param  null|array<mixed>  $filters
         */
        public function loadPresetView(string $presetView, ?array $filters = null, bool $resetTable = true, bool $isActive = true): void
        {
            if ($presetView === $this->activePresetView) {
                $this->resetTable();

                return;
            }

            $this->forceLoadPresetView($presetView, $filters, $resetTable, $isActive);
        }
    }
} else {
    PackageException::missing('AdvancedTables');
}
