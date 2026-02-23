<?php

namespace Mpietrucha\Filament\Essentials\Commands;

use Illuminate\Console\Command;
use Mpietrucha\Utility\Filesystem;
use Mpietrucha\Utility\Str;

class UpgradeFilament extends Command
{
    /**
     * @var string
     */
    protected $signature = 'essentials:upgrade-filament';

    /**
     * @var string
     */
    protected $description = 'Patch Filament source to enabled additional features';

    public function handle(): void
    {
        $this->select();
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
            Str::sprintf('%s,o.textContent=t', $indicator),
            Str::sprintf('%s,this.isHtmlAllowed?o.innerHTML=t:o.textContent=t', $indicator),
            $select
        );
    }

    protected function isRelatedToOperator(): void
    {
        $file = base_path('vendor/filament/query-builder/src/Constraints/RelationshipConstraint/Operators/IsRelatedToOperator.php');

        if (Filesystem::unexists($file)) {
            return;
        }

        Filesystem::replaceInFile('return [$field];', 'return [$field->allowHtml()];', $file);
    }
}
