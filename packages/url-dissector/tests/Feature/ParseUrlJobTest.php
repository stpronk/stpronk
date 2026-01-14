<?php

use Stpronk\UrlDissector\Jobs\ParseUrlJob;
use Stpronk\UrlDissector\Services\UrlDissectorService;
use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can be dispatched', function () {
    Queue::fake();

    ParseUrlJob::dispatch('https://example.com');

    Queue::assertPushed(ParseUrlJob::class);
});

it('parses the url when handled', function () {
    $url = 'https://example.com/job-test';
    $job = new ParseUrlJob($url);

    $job->handle(app(UrlDissectorService::class));

    expect(Url::where('normalized_url', $url)->exists())->toBeTrue();
});
