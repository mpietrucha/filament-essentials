<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Commands;

use Illuminate\Console\Command;
use Mpietrucha\Support\Filesystem;

class UpgradeFilament extends Command
{
    /**
     * @var string
     */
    #[\Override]
    protected $signature = 'essentials:upgrade-filament';

    /**
     * @var string
     */
    #[\Override]
    protected $description = 'Patch Filament source to enabled additional features';

    public function handle(): void
    {
        $this->select();
        $this->selectFilter();
        $this->isRelatedToOperator();

        $this->info('Filament upgraded successfully.');
    }

    protected function select(): void
    {
        $select = public_path('js/filament/forms/components/select.js');

        if (Filesystem::unexists($select)) {
            return;
        }

        $indicator = 'o.className="fi-dropdown-header"';

        Filesystem::replaceInFile(
            sprintf('%s,o.textContent=t', $indicator),
            sprintf('%s,this.isHtmlAllowed?o.innerHTML=t:o.textContent=t', $indicator),
            $select
        );
    }

    protected function selectFilter(): void
    {
        $selectFilter = base_path('vendor/filament/tables/src/Filters/SelectFilter.php');

        if (Filesystem::unexists($selectFilter)) {
            return;
        }

        Filesystem::replaceInFile('return $field;', 'return $field->allowHtml();', $selectFilter);
    }

    protected function isRelatedToOperator(): void
    {
        $isRelatedToOperator = base_path('vendor/filament/query-builder/src/Constraints/RelationshipConstraint/Operators/IsRelatedToOperator.php');

        if (Filesystem::unexists($isRelatedToOperator)) {
            return;
        }

        Filesystem::replaceInFile('return [$field];', 'return [$field->allowHtml()];', $isRelatedToOperator);
    }
}
