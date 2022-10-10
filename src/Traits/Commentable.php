<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Traits;

use Alfonsobries\LaravelCommentable\Contracts\CanCommentInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Commentable
{
    public function comments(): MorphMany
    {
        return $this->morphMany(config('laravel-commentable.models.comment'), 'commentable');
    }

    public function addComment(string $comment, array $extraAttributes = []): Model
    {
        return $this->comments()->create([
            'comment'    => $comment,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            ...$extraAttributes,
        ]);
    }

    public function addCommentFrom(CanCommentInterface $agent, string $comment, array $extraAttributes = []): Model
    {
        return $this->addComment($comment, [
            'agent_id' => $agent->getKey(),
            ...$extraAttributes,
        ]);
    }
}
