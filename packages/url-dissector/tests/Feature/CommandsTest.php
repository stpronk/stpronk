<?php

use Illuminate\Support\Facades\Artisan;
use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Models\Host;
use Stpronk\UrlDissector\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can run cleanup command', function () {
    // Create an orphaned host
    Host::create([
        'full_host' => 'orphaned.com',
        'domain' => 'orphaned',
        'tld' => 'com'
    ]);

    expect(Host::count())->toBe(1);

    Artisan::call('url-dissector:cleanup');

    expect(Host::count())->toBe(0);
});

it('can run import command', function () {
    $tempFile = tempnam(sys_get_temp_dir(), 'urls');
    File::put($tempFile, "https://google.com\nhttps://yahoo.com");

    Artisan::call('url-dissector:import', ['file' => $tempFile]);

    expect(Url::count())->toBe(2);

    unlink($tempFile);
});

it('can run parse command', function () {
    Artisan::call('url-dissector:parse', ['url' => 'https://bing.com']);

    expect(Url::where('normalized_url', 'https://bing.com/')->exists())->toBeTrue();
});
