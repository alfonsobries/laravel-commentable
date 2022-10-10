<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Traits;

use Alfonsobries\LaravelCommentable\Contracts\CanCommentInterface;
use Alfonsobries\LaravelCommentable\Enums\CommentReactionTypeEnum;
use Alfonsobries\LaravelCommentable\Models\CommentReaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasReactions
{
    public function react(CommentReactionTypeEnum $type, ?CanCommentInterface $agent = null): CommentReaction
    {
        return $this->reactions()->create([
            'type' => $type,
            'agent_id' => $agent?->id,
            'comment_id' => $this->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function like(?CanCommentInterface $agent = null): CommentReaction
    {
        return $this->react(CommentReactionTypeEnum::Like, $agent);
    }

    public function dislike(?CanCommentInterface $agent = null): CommentReaction
    {
        return $this->react(CommentReactionTypeEnum::Dislike, $agent);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(config('laravel-commentable.models.comment_reaction'), 'comment_id');
    }

    public function likes(): HasMany
    {
        return $this->reactions()->likes();
    }

    public function dislikes(): HasMany
    {
        return $this->reactions()->dislikes();
    }

    // public function scopeNotApproved(Builder $query): Builder
    // {
    //     return $query->whereNull('approved_at')->orWhere('approved_at', '>', Carbon::now());
    // }

    // public function approve(): self
    // {
    //     $this->approved_at = Carbon::now();
    //     $this->save();

    //     return $this;
    // }

    // public function unapprove(): self
    // {
    //     $this->approved_at = null;
    //     $this->save();

    //     return $this;
    // }
}
