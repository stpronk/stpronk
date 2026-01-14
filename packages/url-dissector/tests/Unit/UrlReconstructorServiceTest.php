<?php

use Stpronk\UrlDissector\Services\UrlReconstructorService;
use Stpronk\UrlDissector\Services\UrlDissectorService;
use Stpronk\UrlDissector\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can rebuild a url from id', function () {
    $dissector = app(UrlDissectorService::class);
    $url = 'https://example.com/path?q=1#frag';
    $model = $dissector->store($url);

    $reconstructor = app(UrlReconstructorService::class);
    $rebuilt = $reconstructor->rebuild($model->id);

    expect($rebuilt)->toBe($url);
});

it('can verify a url reconstruction', function () {
    $dissector = app(UrlDissectorService::class);
    $url = 'https://example.com/test';
    $model = $dissector->store($url);

    $reconstructor = app(UrlReconstructorService::class);
    expect($reconstructor->verify($model->id))->toBeTrue();
});

it('can rebuild from components array', function () {
    $reconstructor = app(UrlReconstructorService::class);
    $components = [
        'scheme' => 'http',
        'host' => 'example.org',
        'port' => 8080,
        'path' => '/api',
        'query' => 'a=b',
        'fragment' => 'start'
    ];

    $rebuilt = $reconstructor->rebuildFromComponents($components);
    expect($rebuilt)->toBe('http://example.org:8080/api?a=b#start');
});
