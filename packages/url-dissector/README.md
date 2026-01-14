# URL Dissector

Create a standalone Laravel package called "URL Dissector" that parses URLs into component parts (host, path, query parameters, etc.), stores them in a normalized database structure, provides reconstruction capabilities, and includes comprehensive analytics - all with a FilamentPHP v4 admin interface.

## Installation

You can install the package via composer:

```bash
composer require stpronk/url-dissector
```

Then run the installation command:

```bash
php artisan url-dissector:install
```

## Features

- Parse URLs into scheme, host, port, path, query parameters, and fragment.
- Normalize URLs (remove www, trailing slashes, etc.).
- Store hierarchical path segments.
- FilamentPHP v4 integration with resources and widgets.
- Analytics dashboard.

## Usage

### Parsing a URL

```php
use Stpronk\UrlDissector\Facades\UrlDissector;

// Synchronous parsing and storage
$url = UrlDissector::store('https://www.example.com/api/v1/users?active=true');

// Queued parsing (asynchronous)
UrlDissector::dispatch('https://www.example.com/huge-path');
```

### Artisan Commands

```bash
# Install the package
php artisan url-dissector:install

# Parse a single URL
php artisan url-dissector:parse {url}

# Import URLs from a file (CSV or text)
php artisan url-dissector:import {file} --queue

# Re-parse all stored URLs
php artisan url-dissector:reparse --queue

# Cleanup orphaned host and path records
php artisan url-dissector:cleanup
```

### Reconstructing a URL

```php
use Stpronk\UrlDissector\Services\UrlReconstructorService;

$reconstructor = app(UrlReconstructorService::class);
$originalUrl = $reconstructor->rebuild($url->id);
```

### Polymorphic Relations

Add the `HasUrls` trait to your model:

```php
use Stpronk\UrlDissector\Traits\HasUrls;

class Project extends Model
{
    use HasUrls;
}
```

## Analytics

Access the Analytics Dashboard in your Filament panel to see:
- Top Hosts
- Path Depth Distribution
- Query Parameter Frequency
- Scheme Distribution
- URLs Over Time

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
