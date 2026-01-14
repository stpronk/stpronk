<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Commands;

use Illuminate\Console\Command;
use Stpronk\UrlDissector\Services\UrlDissectorService;

class ParseUrlCommand extends Command
{
    protected $signature = 'url-dissector:parse {url}';

    protected $description = 'Parse a single URL';

    public function handle(UrlDissectorService $service): int
    {
        $url = $this->argument('url');

        if (!$service->validate($url)) {
            $this->error('Invalid URL provided.');
            return self::FAILURE;
        }

        $this->info("Parsing URL: {$url}");
        $urlModel = $service->store($url);

        $this->info("URL parsed successfully. ID: {$urlModel->id}");
        $this->line("Normalized: " . app(\Stpronk\UrlDissector\Services\UrlReconstructorService::class)->rebuild($urlModel->id));

        return self::SUCCESS;
    }
}
