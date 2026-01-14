<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stpronk\UrlDissector\Services\UrlStatusCheckerService;

class CheckUrlStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(UrlStatusCheckerService $service): void
    {
        $service->checkAll();
    }
}
