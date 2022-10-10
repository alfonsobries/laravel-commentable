<?php

namespace Alfonsobries\LaravelCommentable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Alfonsobries\LaravelCommentable\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Alfonsobries\LaravelCommentable\Enums\CommentReactionTypeEnum;

class CommentReaction extends Model
{
    use UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'type',
        'agent_id',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => CommentReactionTypeEnum::class,
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(config('laravel-commentable.models.agent'), 'agent_id');
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('laravel-commentable.tables.comment_reaction');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(config('laravel-commentable.models.comment'), 'comment_id');
    }

    public function scopeLikes(Builder $query): Builder
    {
        return $query->where('type', CommentReactionTypeEnum::Like);
    }

    public function scopeDislikes(Builder $query): Builder
    {
        return $query->where('type', CommentReactionTypeEnum::Dislike);
    }
}
