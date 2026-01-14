<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'url-dissector:install';

    protected $description = 'Setup the URL Dissector package';

    public function handle(): int
    {
        $this->info('Installing URL Dissector...');

        $this->publishConfig();
        $this->publishMigrations();

        if ($this->confirm('Do you want to run the migrations now?')) {
            $this->call('migrate');
        }

        $this->info('URL Dissector installed successfully.');

        return self::SUCCESS;
    }

    protected function publishConfig(): void
    {
        $this->call('vendor:publish', [
            '--provider' => "Stpronk\UrlDissector\UrlDissectorServiceProvider",
            '--tag' => 'url-dissector-config',
        ]);
    }

    protected function publishMigrations(): void
    {
        $this->call('vendor:publish', [
            '--provider' => "Stpronk\UrlDissector\UrlDissectorServiceProvider",
            '--tag' => 'url-dissector-migrations',
        ]);
    }
}
