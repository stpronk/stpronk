<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Models\Host;
use Stpronk\UrlDissector\Models\Path;

class UrlFactory extends Factory
{
    protected $model = Url::class;

    public function definition(): array
    {
        return [
            'normalized_url' => $this->faker->url(),
            'host_id' => Host::factory(),
            'path_id' => Path::factory(),
            'scheme' => 'https',
            'port' => null,
            'query_string' => null,
            'fragment' => null,
            'parsed_at' => now(),
        ];
    }
}
