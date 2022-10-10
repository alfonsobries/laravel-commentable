<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Alfonsobries\LaravelCommentable\Models\CommentReaction;
use Alfonsobries\LaravelCommentable\Contracts\CanCommentInterface;
use Alfonsobries\LaravelCommentable\Enums\CommentReactionTypeEnum;

/**
 * @property mixed $id
 */
trait HasReactions
{
    public function react(CommentReactionTypeEnum $type, ?CanCommentInterface $agent = null): Model
    {
        return $this->reactions()->create([
            'type' => $type,
            'agent_id' => $agent?->getKey(),
            'comment_id' => $this->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function like(?CanCommentInterface $agent = null): Model
    {
        return $this->react(CommentReactionTypeEnum::Like, $agent);
    }

    public function dislike(?CanCommentInterface $agent = null): Model
    {
        return $this->react(CommentReactionTypeEnum::Dislike, $agent);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(config('laravel-commentable.models.comment_reaction'), 'comment_id');
    }

    public function likes(): HasMany
    {
        /** @var mixed $reactions */
        $reactions = $this->reactions();
        return $reactions->likes();
    }

    public function dislikes(): HasMany
    {
        /** @var mixed $reactions */
        $reactions = $this->reactions();
        return $reactions->dislikes();
    }
}
