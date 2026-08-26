<?php

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Concerns;

use Archilex\AdvancedTables\AdvancedTables;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;

if (trait_exists(AdvancedTables::class)) {
    /**
     * @phpstan-type ViewFilters null|array<mixed>
     */
    trait AdvancedTablesWithTogglableViews
    {
        use AdvancedTables {
            loadPresetView as forceLoadPresetView;
            loadUserView as forceLoadUserView;
        }

        /**
         * @param  ViewFilters  $filters
         */
        public function loadUserView(string $userView, ?array $filters = null, bool $resetTable = true, bool $isActive = true): void
        {
            if ($userView === $this->activeUserView) {
                $this->resetTable();

                return;
            }

            $this->forceLoadUserView($userView, $filters, $resetTable, $isActive);
        }

        /**
         * @param  ViewFilters  $filters
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
