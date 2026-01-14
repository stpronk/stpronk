<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Stpronk\UrlDissector\Services\UrlDissectorService;

class ParseUrlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $url,
        protected ?Model $urlable = null
    ) {}

    public function handle(UrlDissectorService $service): void
    {
        $service->store($this->url, $this->urlable);
    }
}
