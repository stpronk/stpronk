<?php

namespace Stpronk\Purchases\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Stpronk\Essentials\Traits\ModelHasShareable;
use Stpronk\Purchases\Enums\PurchaseItemPriority;
use Stpronk\Purchases\Enums\PurchaseItemStatus;
use Stpronk\Todos\Models\Todo;

class PurchaseItem extends Model
{
    use ModelHasShareable;

    protected $table = 'purchase_items';

    protected $fillable = [
        'name',
        'quantity',
        'url',
        'price',
        'status',
        'priority',
        'user_id',
        'todo_id',
        'is_wishlist',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'status' => PurchaseItemStatus::class,
        'priority' => PurchaseItemPriority::class,
        'price' => 'decimal:2',
        'is_wishlist' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (PurchaseItem $model) {
            if (empty($model->user_id) && Auth::id()) {
                $model->user_id = Auth::id();
            }
        });

        static::addGlobalScope('access', function (Builder $builder) {
            $builder->where('user_id', Auth::id());
            static::searchableGlobalScopeBuilder($builder);

            if (class_exists('Stpronk\Todos\Models\Todo')) {
                $builder->orWhereHas('todo', function (Builder $query) {
                    $query->withoutGlobalScope('access')
                        ->whereHas('shareables', fn($builder) => $builder->where('shared_with', Auth::id()));
                });
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', 'App\Models\User'));
    }

    public function todo(): BelongsTo
    {
        return $this->belongsTo('Stpronk\Todos\Models\Todo');
    }

    public function isShared(): bool
    {
        if ($this->shareables()->exists()) {
            return true;
        }

        if (class_exists('Stpronk\Todos\Models\Todo') && $this->todo_id) {
            return $this->todo()->withoutGlobalScope('access')->whereHas('shareables')->exists();
        }

        return false;
    }

    public function getSharedWithMeAttribute(): bool
    {
        if ($this->attributes['shared_with_me'] ?? 0) {
            return true;
        }

        if (class_exists('Stpronk\Todos\Models\Todo') && $this->todo_id) {
            return $this->todo()
                ->withoutGlobalScope('access')
                ->whereHas('shareables', fn($builder) => $builder->where('shared_with', Auth::id()))
                ->exists();
        }

        return false;
    }
}
