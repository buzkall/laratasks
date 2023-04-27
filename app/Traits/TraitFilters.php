<?php

namespace App\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait TraitFilters
{
    public function scopeCompletedOrUntagged($query)
    {
        return $query->whereNotNull('completed_at')
                     ->orWhereDoesntHave('tags');
    }
}
