<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Stpronk\UrlDissector\Models\Url;

trait HasUrls
{
    public function urls(): MorphToMany
    {
        return $this->morphToMany(
            config('url-dissector.models.url'),
            'urlable',
            config('url-dissector.table_prefix') . 'urlables'
        )->withTimestamps();
    }
}
