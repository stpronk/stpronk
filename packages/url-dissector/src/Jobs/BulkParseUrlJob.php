<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stpronk\UrlDissector\Services\UrlDissectorService;

class BulkParseUrlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected array $urls
    ) {}

    public function handle(UrlDissectorService $service): void
    {
        foreach ($this->urls as $url) {
            try {
                $service->store($url);
            } catch (\Exception $e) {
                // Skip
            }
        }
    }
}
