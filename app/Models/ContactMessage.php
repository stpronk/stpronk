<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'message',
        'is_spam',
        'spam_reason',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_spam' => 'boolean',
    ];

    public function scopeSpam(Builder $query): Builder
    {
        return $query->where('is_spam', true);
    }

    public function scopeNotSpam(Builder $query): Builder
    {
        return $query->where('is_spam', false);
    }
}
