<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Commands;

use Illuminate\Console\Command;
use Stpronk\UrlDissector\Models\Host;
use Stpronk\UrlDissector\Models\Path;

class CleanupCommand extends Command
{
    protected $signature = 'url-dissector:cleanup';

    protected $description = 'Remove orphaned hosts and paths';

    public function handle(): int
    {
        $this->info('Cleaning up orphaned records...');

        $orphanedHosts = config('url-dissector.models.host')::doesntHave('urls')->delete();
        $this->info("Removed {$orphanedHosts} orphaned hosts.");

        $orphanedPaths = config('url-dissector.models.path')::doesntHave('urls')->delete();
        $this->info("Removed {$orphanedPaths} orphaned paths.");

        return self::SUCCESS;
    }
}
