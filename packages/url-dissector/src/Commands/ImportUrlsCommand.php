<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Commands;

use Illuminate\Console\Command;
use Stpronk\UrlDissector\Services\UrlDissectorService;
use Illuminate\Support\Facades\File;

class ImportUrlsCommand extends Command
{
    protected $signature = 'url-dissector:import {file} {--queue : Queue the parsing jobs}';

    protected $description = 'Import URLs from a file (CSV or text)';

    public function handle(UrlDissectorService $service): int
    {
        $filePath = $this->argument('file');

        if (!File::exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return self::FAILURE;
        }

        $content = File::get($filePath);
        $urls = preg_split('/\r\n|\r|\n/', $content);
        $urls = array_filter(array_map('trim', $urls));

        $this->info("Importing " . count($urls) . " URLs...");

        $bar = $this->output->createProgressBar(count($urls));
        $bar->start();

        foreach ($urls as $url) {
            try {
                if ($service->validate($url)) {
                    if ($this->option('queue')) {
                        $service->dispatch($url);
                    } else {
                        $service->store($url);
                    }
                }
            } catch (\Exception $e) {
                // Log or skip
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Import completed.');

        return self::SUCCESS;
    }
}
