<?php

namespace Mpietrucha\Filament\Essentials\Commands;

use Illuminate\Console\Command;
use Mpietrucha\Utility\Filesystem;
use Mpietrucha\Utility\Str;

class PatchComponents extends Command
{
    /**
     * @var string
     */
    protected $signature = 'essentials:patch-components';

    /**
     * @var string
     */
    protected $description = '';

    public function handle(): void
    {
        $this->select();

        $this->info('Components patched successfully.');
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
}
