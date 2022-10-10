<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Commentable
{
    public function comments(): MorphMany
    {
        return $this->morphMany(config('laravel-commentable.models.comment'), 'commentable');
    }
}
