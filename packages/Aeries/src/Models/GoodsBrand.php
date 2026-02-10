<?php

namespace Stpronk\Aeries\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Stpronk\Aeries\Enums\GoodsBrandType;

class GoodsBrand extends Model
{
    use SoftDeletes;

    protected $table = 'aeries_goods_brands';

    protected $fillable = [
        'user_id',
        'name',
        'type',
    ];

    protected $casts = [
        'type' => GoodsBrandType::class,
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (GoodsBrand $model) {
            if (empty($model->user_id) && Auth::id()) {
                $model->user_id = Auth::id();
            }
        });

        static::addGlobalScope('access', function (Builder $builder) {
            $builder->where('user_id', Auth::id());
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', 'App\Models\User'));
    }

    public function goodsEntities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GoodsEntity::class, 'goods_brand_id');
    }
}
