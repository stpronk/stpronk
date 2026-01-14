<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Path extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Stpronk\UrlDissector\Database\Factories\PathFactory::new();
    }

    protected $fillable = [
        'full_path',
        'hash',
        'depth',
    ];

    public function getTable(): string
    {
        return config('url-dissector.table_prefix') . 'paths';
    }

    public function segments(): HasMany
    {
        return $this->hasMany(config('url-dissector.models.path_segment'));
    }

    public function urls(): HasMany
    {
        return $this->hasMany(config('url-dissector.models.url'));
    }
}
