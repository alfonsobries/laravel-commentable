<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Traits;

use Alfonsobries\LaravelCommentable\Contracts\CommentableInterface;
use Alfonsobries\LaravelCommentable\Models\Comment;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanComment
{
    public function comment(CommentableInterface $commentable, string $comment): Comment
    {
        return $commentable->comments()->create([
            'comment'    => $comment,
            'agent_id'   => $this->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(config('laravel-commentable.models.comment'));
    }
}
