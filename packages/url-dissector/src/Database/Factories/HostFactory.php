<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Stpronk\UrlDissector\Models\Host;

class HostFactory extends Factory
{
    protected $model = Host::class;

    public function definition(): array
    {
        $domain = $this->faker->domainName();
        return [
            'full_host' => $domain,
            'domain' => explode('.', $domain)[0],
            'tld' => 'com',
            'subdomain' => null,
        ];
    }
}
