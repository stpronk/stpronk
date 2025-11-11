<?php

namespace Stpronk\Todos\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TodoCategory extends Model
{
    protected $table = 'todos_categories';

    protected $fillable = [
        'name',
        'color',
        'user_id',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (TodoCategory $model) {
            if (empty($model->user_id) && \Illuminate\Support\Facades\Auth::id()) {
                $model->user_id = \Illuminate\Support\Facades\Auth::id();
            }
            if (empty($model->color)) {
                $model->color = 'primary';
            }
        });

        static::addGlobalScope('user_id', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->where('user_id', \Illuminate\Support\Facades\Auth::id());
        });
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class, 'todo_category_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
