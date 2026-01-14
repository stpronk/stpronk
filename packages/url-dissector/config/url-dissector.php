<?php

return [
    'table_prefix' => 'url_dissector_',

    'models' => [
        'host' => \Stpronk\UrlDissector\Models\Host::class,
        'path' => \Stpronk\UrlDissector\Models\Path::class,
        'path_segment' => \Stpronk\UrlDissector\Models\PathSegment::class,
        'url' => \Stpronk\UrlDissector\Models\Url::class,
        'url_usage' => \Stpronk\UrlDissector\Models\UrlUsage::class,
        'query_parameter' => \Stpronk\UrlDissector\Models\QueryParameter::class,
    ],

    'parsing' => [
        'normalize_urls' => true,
        'remove_trailing_slash' => true,
        'lowercase_host' => true,
        'extract_www' => true,
        'default_scheme' => 'https',
    ],

    'cache' => [
        'enabled' => true,
        'ttl' => 3600,
        'prefix' => 'url_dissector_',
        'driver' => null, // Use default
    ],

    'queue' => [
        'enabled' => true,
        'connection' => null,
        'queue' => 'default',
        'batch_size' => 100,
    ],

    'analytics' => [
        'cache_results' => true,
        'cache_ttl' => 1800,
    ],

    'filament' => [
        'enabled' => true,
        'resources' => [
            'url' => true,
            'host' => true,
            'path' => true, // Optional
        ],
        'widgets' => [
            'stats_overview' => true,
            'top_hosts' => true,
            'path_depth' => true,
            'query_params' => true,
            'scheme_distribution' => true,
            'urls_over_time' => true,
        ],
        'navigation' => [
            'group' => 'URL Analytics',
            'sort' => 100,
        ],
    ],
];
