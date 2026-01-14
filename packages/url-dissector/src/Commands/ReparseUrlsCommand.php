<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Commands;

use Illuminate\Console\Command;
use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Services\UrlDissectorService;

class ReparseUrlsCommand extends Command
{
    protected $signature = 'url-dissector:reparse {--queue : Queue the re-parsing jobs}';

    protected $description = 'Re-parse all stored URLs';

    public function handle(UrlDissectorService $service): int
    {
        $urls = config('url-dissector.models.url')::all();
        $this->info("Re-parsing " . $urls->count() . " URLs...");

        $bar = $this->output->createProgressBar($urls->count());
        $bar->start();

        foreach ($urls as $urlModel) {
            try {
                if ($this->option('queue')) {
                    $service->dispatch($urlModel->normalized_url);
                } else {
                    $service->store($urlModel->normalized_url);
                }
            } catch (\Exception $e) {
                // Skip
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Re-parsing completed.');

        return self::SUCCESS;
    }
}
