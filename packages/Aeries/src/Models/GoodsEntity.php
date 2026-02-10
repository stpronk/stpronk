<?php

namespace Stpronk\Aeries\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class GoodsEntity extends Model
{
    use SoftDeletes;

    protected $table = 'aeries_goods_entities';

    protected $fillable = [
        'user_id',
        'goods_brand_id',
        'name',
        'type',
        'service',
        'three_d_model',
    ];

    protected $casts = [
        'type' => \Stpronk\Aeries\Enums\GoodsEntityType::class,
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (GoodsEntity $model) {
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

    public function goodsBrand(): BelongsTo
    {
        return $this->belongsTo(GoodsBrand::class, 'goods_brand_id');
    }
}
