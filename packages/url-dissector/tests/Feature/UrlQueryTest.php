<?php

use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Services\UrlDissectorService;
use Stpronk\UrlDissector\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can query urls with null query string correctly', function () {
    $service = app(UrlDissectorService::class);
    $service->store('https://example.com/no-query');
    $service->store('https://example.com/with-query?a=b');

    expect(Url::count())->toBe(2);
    expect(Url::whereNull('query_string')->count())->toBe(1);
    expect(Url::whereNotNull('query_string')->count())->toBe(1);
});
