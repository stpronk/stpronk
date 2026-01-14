<?php

use Stpronk\UrlDissector\Services\PathParserService;
use Stpronk\UrlDissector\Models\Path;
use Stpronk\UrlDissector\Models\PathSegment;
use Stpronk\UrlDissector\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can parse a path into segments', function () {
    $service = new PathParserService();
    $parsed = $service->parse('/api/v1/users');

    expect($parsed)->toBeArray()
        ->and($parsed['full_path'])->toBe('/api/v1/users')
        ->and($parsed['segments'])->toBe(['api', 'v1', 'users'])
        ->and($parsed['depth'])->toBe(3)
        ->and($parsed['hash'])->toBe(md5('/api/v1/users'));
});

it('finds or creates a path with segments', function () {
    $service = new PathParserService();

    $path = $service->findOrCreate('/a/b');

    expect($path)->toBeInstanceOf(Path::class)
        ->and($path->full_path)->toBe('/a/b')
        ->and($path->depth)->toBe(2);

    expect(PathSegment::count())->toBe(2);

    $segments = $path->segments()->orderBy('depth')->get();
    expect($segments[0]->segment)->toBe('a')
        ->and($segments[0]->depth)->toBe(0)
        ->and($segments[1]->segment)->toBe('b')
        ->and($segments[1]->depth)->toBe(1)
        ->and($segments[1]->parent_segment_id)->toBe($segments[0]->id);
});

it('deduplicates paths by hash', function () {
    $service = new PathParserService();

    $path1 = $service->findOrCreate('/test');
    $path2 = $service->findOrCreate('/test');

    expect($path1->id)->toBe($path2->id);
    expect(Path::count())->toBe(1);
});
