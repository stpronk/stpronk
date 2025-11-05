<?php

namespace Stpronk\Assets\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Asset extends Model
{

    use HasFactory;

    protected $table = 'assets';

    protected $fillable
        = [
            'name',
            'category_id',
            'price_cents',
            'status',
            'take_profit_cents',
            'closed_at',
        ];

    protected $casts
        = [
            'price_cents'       => 'integer',
            'take_profit_cents' => 'integer',
            'closed_at'         => 'datetime',
        ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_id', function (Builder $builder) {
            $builder->where('user_id', Auth::id());
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
