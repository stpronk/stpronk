<?php

namespace Stpronk\Essentials\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Shareables extends Model
{
    use SoftDeletes;

    protected $table = 'shareables';

    protected $fillable
        = [
            'shared_with',
            'shared_by',
            'shared_at',
        ];

    protected $with = [
        'sharedWith',
        'sharedBy',
    ];

    protected $casts = [
        'shared_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->shared_by) && Auth::id()) {
                $model->shared_by = Auth::id();
            }

            if (empty($model->shared_at)) {
                $model->shared_at = Carbon::now();
            }
        });
    }

    public function sharedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_by');
    }

    public function sharedWith(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_with');
    }

    public function shareable(): MorphTo
    {
        return $this->morphTo();
    }
}
