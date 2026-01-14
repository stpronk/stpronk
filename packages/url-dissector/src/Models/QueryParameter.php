<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueryParameter extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'url_id',
        'key',
        'value',
        'order',
    ];

    public function getTable(): string
    {
        return config('url-dissector.table_prefix') . 'query_parameters';
    }

    public function url(): BelongsTo
    {
        return $this->belongsTo(config('url-dissector.models.url'));
    }
}
