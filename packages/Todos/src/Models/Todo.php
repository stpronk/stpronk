<?php

namespace Stpronk\Todos\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Sytatsu\Essentials\Traits\ModelHasShareable;

class Todo extends Model
{
    use ModelHasShareable;

    protected $table = 'todos';

    protected $fillable = [
        'title',
        'priority',
        'notes',
        'todo_category_id',
        'due_date',
        'completed_at',
        'completed_comment',
        'user_id',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'due_date' => 'date',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Todo $model) {
            if (empty($model->user_id) && Auth::id()) {
                $model->user_id = Auth::id();
            }
        });

        static::addGlobalScope('access', function (Builder $builder) {
            $builder->where('user_id', Auth::id());
            static::searchableGlobalScopeBuilder($builder);

            if (class_exists('Stpronk\Purchases\Models\PurchaseItem')) {
                $builder->orWhereHas('purchaseItems', function (Builder $query) {
                    $query->withoutGlobalScope('access')
                        ->whereHas('shareables', fn($builder) => $builder->where('shared_with', Auth::id()));
                });
            }
        });
    }

    public function isShared(): bool
    {
        if ($this->shareables()->exists()) {
            return true;
        }

        if (class_exists('Stpronk\Purchases\Models\PurchaseItem')) {
            if ($this->purchaseItems()->withoutGlobalScope('access')->whereHas('shareables')->exists()) {
                return true;
            }
        }

        return false;
    }

    public function getSharedWithMeAttribute(): bool
    {
        if ($this->attributes['shared_with_me'] ?? 0) {
            return true;
        }

        if (class_exists('Stpronk\Purchases\Models\PurchaseItem')) {
            return $this->purchaseItems()
                ->withoutGlobalScope('access')
                ->whereHas('shareables', fn($builder) => $builder->where('shared_with', Auth::id()))
                ->exists();
        }

        return false;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TodoCategory::class, 'todo_category_id' );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('filament-todos.user_model'));
    }

    public function purchaseItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('Stpronk\Purchases\Models\PurchaseItem');
    }
}
