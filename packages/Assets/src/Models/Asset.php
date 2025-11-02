<?php

namespace Stpronk\Assets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';

    protected $fillable = [
        'name',
        'category_id',
        'price_cents',
        'status',
        'take_profit_cents',
        'closed_at',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'take_profit_cents' => 'integer',
        'closed_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
