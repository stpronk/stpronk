<?php

namespace Stpronk\Todos\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Stpronk\Essentials\Traits\ModelHasShareable;

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
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TodoCategory::class, 'todo_category_id' );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('filament-todos.user_model'));
    }
}
