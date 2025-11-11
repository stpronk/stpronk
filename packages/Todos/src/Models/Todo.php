<?php

namespace Stpronk\Todos\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Todo extends Model
{
    protected $table = 'todos';

    protected $fillable = [
        'title',
        'priority',
        'notes',
        'todo_category_id',
        'completed_at',
        'completed_comment',
        'user_id',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Todo $model) {
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
        return $this->belongsTo(TodoCategory::class, 'todo_category_id' );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('filament-todos.user_model'));
    }
}
