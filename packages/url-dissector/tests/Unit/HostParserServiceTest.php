<?php

use Stpronk\UrlDissector\Services\HostParserService;
use Stpronk\UrlDissector\Models\Host;
use Stpronk\UrlDissector\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Http::fake([
        'publicsuffix.org/*' => Http::response("com\norg\nnet", 200),
    ]);
    Cache::forget('url-dissector-pdp-rules');
});

it('can parse a host with pdp rules', function () {
    $service = new HostParserService();
    $parsed = $service->parse('www.example.com');

    expect($parsed)->toBeArray()
        ->and($parsed['full_host'])->toBe('www.example.com')
        ->and($parsed['domain'])->toBe('example')
        ->and($parsed['tld'])->toBe('com')
        ->and($parsed['subdomain'])->toBe('www');
});

it('can parse a complex host', function () {
    $service = new HostParserService();
    $parsed = $service->parse('sub.deep.example.org');

    expect($parsed['subdomain'])->toBe('sub.deep')
        ->and($parsed['domain'])->toBe('example')
        ->and($parsed['tld'])->toBe('org');
});

it('finds or creates a host record', function () {
    $service = new HostParserService();

    $host1 = $service->findOrCreate('example.com');
    $host2 = $service->findOrCreate('EXAMPLE.COM');

    expect($host1->id)->toBe($host2->id)
        ->and($host1->full_host)->toBe('example.com');

    expect(Host::count())->toBe(1);
});

it('handles parsing failure with fallback', function () {
    Http::fake([
        'publicsuffix.org/*' => Http::response('Error', 500),
    ]);
    Cache::forget('url-dissector-pdp-rules');

    $service = new HostParserService();
    $parsed = $service->parse('localhost');

    expect($parsed['full_host'])->toBe('localhost')
        ->and($parsed['domain'])->toBe('localhost')
        ->and($parsed['tld'])->toBe('');
});
