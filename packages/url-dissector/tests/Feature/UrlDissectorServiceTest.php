<?php

use Stpronk\UrlDissector\Services\UrlDissectorService;
use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Models\Host;
use Stpronk\UrlDissector\Models\Path;
use Stpronk\UrlDissector\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can parse a basic url', function () {
    $service = app(UrlDissectorService::class);
    $url = 'https://www.example.com/api/v1/users?active=true#section';

    $parsed = $service->parse($url);

    expect($parsed['scheme'])->toBe('https')
        ->and($parsed['host'])->toBe('example.com')
        ->and($parsed['path'])->toBe('/api/v1/users')
        ->and($parsed['query_parameters']['active'])->toBe('true')
        ->and($parsed['fragment'])->toBe('section');
});

it('can store a url and creates normalized records', function () {
    $service = app(UrlDissectorService::class);
    $url = 'https://www.example.com/test';
    $normalizedUrl = 'https://example.com/test';

    $model = $service->store($url);

    expect($model)->toBeInstanceOf(Url::class)
        ->and($model->normalized_url)->toBe($normalizedUrl)
        ->and($model->host->full_host)->toBe('example.com')
        ->and($model->path->full_path)->toBe('/test');

    expect(Host::count())->toBe(1)
        ->and(Path::count())->toBe(1);
});

it('deduplicates hosts and paths', function () {
    $service = app(UrlDissectorService::class);

    $service->store('https://example.com/a');
    $service->store('https://example.com/b');

    expect(Host::count())->toBe(1)
        ->and(Path::count())->toBe(2);
});

it('handles ownerless urls deduplication', function () {
    $service = app(UrlDissectorService::class);
    $url = 'https://example.com/unique';

    $model1 = $service->store($url);
    $model2 = $service->store($url);

    expect(Url::count())->toBe(1)
        ->and($model1->id)->toBe($model2->id);

    $service->store('https://example.com/other');
    expect(Url::count())->toBe(2);
});

it('supports bulk store', function () {
    $service = app(UrlDissectorService::class);
    $urls = ['https://a.com', 'https://b.com'];

    $results = $service->bulkStore($urls);

    expect($results)->toHaveCount(2)
        ->and(Url::count())->toBe(2);
});
