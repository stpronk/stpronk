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
    ];

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class, 'todo_category_id');
    }
}
