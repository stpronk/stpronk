<?php

namespace Stpronk\Essentials\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Stpronk\Essentials\Models\Shareables;

trait ModelHasShareable
{
    protected static string $OWNER_FIELD = 'user_id';

    public function shareables(): MorphMany
    {
        return $this->morphMany(Shareables::class, 'shareable');
    }

    public function isShared(): bool
    {
        return (bool)$this->shareables->first();
    }

    public function getOwnerField(): string
    {
        return self::$OWNER_FIELD;
    }

    protected static function searchableGlobalScopeBuilder(Builder $builder): Builder
    {
        return $builder
            ->withCount(['shareables as shared_with_me' => fn($builder) => $builder->where('shared_with', Auth::id())])
            ->orWhereHas('shareables', fn($builder) => $builder->where('shared_with', Auth::id()));
    }
}
