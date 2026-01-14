<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Stpronk\UrlDissector\Commands\InstallCommand;
use Stpronk\UrlDissector\Commands\ParseUrlCommand;
use Stpronk\UrlDissector\Commands\ImportUrlsCommand;
use Stpronk\UrlDissector\Commands\CleanupCommand;
use Stpronk\UrlDissector\Commands\ReparseUrlsCommand;

class UrlDissectorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('url-dissector')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations([
                '2025_12_24_100001_create_url_dissector_hosts_table',
                '2025_12_24_100002_create_url_dissector_paths_table',
                '2025_12_24_100003_create_url_dissector_path_segments_table',
                '2025_12_24_100004_create_url_dissector_urls_table',
                '2025_12_24_100005_create_url_dissector_query_parameters_table',
                '2025_12_24_100006_add_is_valid_to_url_dissector_urls_table',
                '2025_12_24_100007_convert_urls_to_many_to_many',
                '2025_12_24_100008_rename_original_url_to_normalized_url_in_urls_table',
                '2025_12_24_100009_make_columns_nullable_in_urls_table',
                '2026_01_14_080000_add_online_status_to_urls_table',
                '2026_01_14_081000_change_is_online_to_enum_in_urls_table',
            ])
            ->hasCommands([
                InstallCommand::class,
                ParseUrlCommand::class,
                ImportUrlsCommand::class,
                CleanupCommand::class,
                ReparseUrlsCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(\Stpronk\UrlDissector\Services\UrlDissectorService::class);
        $this->app->singleton(\Stpronk\UrlDissector\Services\UrlReconstructorService::class);
        $this->app->singleton(\Stpronk\UrlDissector\Services\UrlAnalyticsService::class);
        $this->app->singleton(\Stpronk\UrlDissector\Services\HostParserService::class);
        $this->app->singleton(\Stpronk\UrlDissector\Services\PathParserService::class);
        $this->app->singleton(\Stpronk\UrlDissector\Services\UrlStatusCheckerService::class);
    }

    public function packageBooted(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
