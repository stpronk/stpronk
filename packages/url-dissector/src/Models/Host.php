<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Host extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Stpronk\UrlDissector\Database\Factories\HostFactory::new();
    }

    protected $fillable = [
        'full_host',
        'domain',
        'tld',
        'subdomain',
    ];

    public function getTable(): string
    {
        return config('url-dissector.table_prefix') . 'hosts';
    }

    public function urls(): HasMany
    {
        return $this->hasMany(config('url-dissector.models.url'));
    }
}
