<?php

use Stpronk\UrlDissector\Services\UrlAnalyticsService;
use Stpronk\UrlDissector\Services\UrlDissectorService;
use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    config(['url-dissector.analytics.cache_results' => false]);
});

it('calculates top hosts correctly', function () {
    $dissector = app(UrlDissectorService::class);
    $dissector->store('https://a.com/1');
    $dissector->store('https://a.com/2');
    $dissector->store('https://b.com/1');

    $analytics = app(UrlAnalyticsService::class);
    $topHosts = $analytics->topHosts();

    expect($topHosts)->toHaveCount(2)
        ->and($topHosts[0]->count)->toBe(2)
        ->and($topHosts[0]->host->full_host)->toBe('a.com')
        ->and($topHosts[1]->count)->toBe(1)
        ->and($topHosts[1]->host->full_host)->toBe('b.com');
});

it('calculates path depth distribution', function () {
    $dissector = app(UrlDissectorService::class);
    $dissector->store('https://example.com/'); // depth 0
    $dissector->store('https://example.com/a'); // depth 1
    $dissector->store('https://example.com/a/b'); // depth 2

    $analytics = app(UrlAnalyticsService::class);
    $dist = $analytics->pathDepthDistribution();

    expect($dist)->toBeArray()
        ->and($dist[0])->toBe(1)
        ->and($dist[1])->toBe(1)
        ->and($dist[2])->toBe(1);
});

it('calculates scheme distribution', function () {
    $dissector = app(UrlDissectorService::class);
    $dissector->store('https://example.com');
    $dissector->store('http://example.com');

    $analytics = app(UrlAnalyticsService::class);
    $dist = $analytics->schemeDistribution();

    expect($dist['https'])->toBe(1)
        ->and($dist['http'])->toBe(1);
});

it('calculates average path depth', function () {
    $dissector = app(UrlDissectorService::class);
    $dissector->store('https://example.com/a'); // 1
    $dissector->store('https://example.com/a/b/c'); // 3

    $analytics = app(UrlAnalyticsService::class);
    expect($analytics->averagePathDepth())->toBe(2.0);
});
