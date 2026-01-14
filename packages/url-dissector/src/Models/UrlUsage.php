<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UrlUsage extends Model
{
    protected $fillable = [
        'url_id',
        'urlable_id',
        'urlable_type',
    ];

    public function getTable(): string
    {
        return config('url-dissector.table_prefix') . 'urlables';
    }

    public function url(): BelongsTo
    {
        return $this->belongsTo(config('url-dissector.models.url'));
    }

    public function urlable(): MorphTo
    {
        return $this->morphTo();
    }
}
