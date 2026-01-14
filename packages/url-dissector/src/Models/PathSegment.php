<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PathSegment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'path_id',
        'segment',
        'depth',
        'order',
        'parent_segment_id',
    ];

    public function getTable(): string
    {
        return config('url-dissector.table_prefix') . 'path_segments';
    }

    public function path(): BelongsTo
    {
        return $this->belongsTo(config('url-dissector.models.path'));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_segment_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_segment_id');
    }
}
