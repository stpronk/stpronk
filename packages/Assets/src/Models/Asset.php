<?php

namespace Stpronk\Assets\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Asset extends Model
{

    protected $table = 'assets';

    protected $fillable
        = [
            'name',
            'category_id',
            'price_cents',
            'status',
            'take_profit_cents',
            'closed_at',
            'user_id'
        ];

    protected $casts
        = [
            'price_cents'       => 'integer',
            'take_profit_cents' => 'integer',
            'closed_at'         => 'datetime',
        ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->user_id) && Auth::id()) {
                $model->user_id = Auth::id();
            }
        });

        static::addGlobalScope('user_id', function (Builder $builder) {
            $builder->where('user_id', Auth::id());
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('filament-assets.user_model'));
    }
}
