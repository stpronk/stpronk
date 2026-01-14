<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

use Stpronk\UrlDissector\Enums\UrlOnlineStatus;

class Url extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Stpronk\UrlDissector\Database\Factories\UrlFactory::new();
    }

    protected $fillable = [
        'normalized_url',
        'host_id',
        'path_id',
        'scheme',
        'port',
        'query_string',
        'fragment',
        'is_valid',
        'is_online',
        'metadata',
        'parsed_at',
        'last_checked_at',
    ];

    protected $casts = [
        'metadata' => 'json',
        'parsed_at' => 'datetime',
        'last_checked_at' => 'datetime',
        'port' => 'integer',
        'is_valid' => 'boolean',
        'is_online' => UrlOnlineStatus::class,
    ];

    public function getTable(): string
    {
        return config('url-dissector.table_prefix') . 'urls';
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(config('url-dissector.models.host'));
    }

    public function path(): BelongsTo
    {
        return $this->belongsTo(config('url-dissector.models.path'));
    }

    public function queryParameters(): HasMany
    {
        return $this->hasMany(config('url-dissector.models.query_parameter'));
    }

    public function usages(): HasMany
    {
        return $this->hasMany(config('url-dissector.models.url_usage'), 'url_id');
    }
}
