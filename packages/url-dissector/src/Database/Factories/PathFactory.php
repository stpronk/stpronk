<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Stpronk\UrlDissector\Models\Path;

class PathFactory extends Factory
{
    protected $model = Path::class;

    public function definition(): array
    {
        $path = '/' . $this->faker->slug();
        return [
            'full_path' => $path,
            'hash' => md5($path),
            'depth' => 1,
        ];
    }
}
