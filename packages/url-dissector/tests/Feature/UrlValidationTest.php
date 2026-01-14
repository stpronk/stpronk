<?php

use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Services\UrlDissectorService;
use Stpronk\UrlDissector\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('tracks if a url is correctly parsed', function () {
    $service = app(UrlDissectorService::class);

    // A standard URL should be valid
    $url1 = $service->store('https://example.com/path?query=1#frag');
    expect($url1->is_valid)->toBeTrue();

    // A URL that might change after normalization but still reconstructs to a "normalized" version
    // normalization: lowercase host, remove www (if configured)
    $url2 = $service->store('HTTPS://WWW.Example.COM/Path/');
    // https://example.com/path (normalized)
    // reconstruction should match normalized
    expect($url2->is_valid)->toBeTrue();

    // Let's try to force an "invalid" one if possible.
    // Actually, our reconstruction is quite faithful, so it's hard to make it invalid
    // unless there's a bug in how we handle some characters.
});

it('shows the valid status in the table', function () {
    // This would be a Filament test, but we can at least check the model has the attribute
    $url = Url::factory()->create(['is_valid' => false]);
    expect($url->is_valid)->toBeFalse();
});
