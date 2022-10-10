<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Models;

use Alfonsobries\LaravelCommentable\Contracts\CanCommentInterface;
use Alfonsobries\LaravelCommentable\Contracts\CommentableInterface;
use Alfonsobries\LaravelCommentable\Enums\CommentReactionTypeEnum;
use Alfonsobries\LaravelCommentable\Traits\Approvable;
use Alfonsobries\LaravelCommentable\Traits\Commentable;
use Alfonsobries\LaravelCommentable\Traits\HasCommentEvents;
use Alfonsobries\LaravelCommentable\Traits\HasReactions;
use Alfonsobries\LaravelCommentable\Traits\UsesUuid;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @property ?Carbon $approved_at
 */
class Comment extends Model implements CommentableInterface
{
    use UsesUuid;
    use HasCommentEvents;
    use HasReactions;
    use SoftDeletes;
    use Approvable;
    use Commentable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'commentable_id',
        'commentable_type',
        'comment',
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
        'approved_at' => 'datetime',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('laravel-commentable.tables.comments');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(config('laravel-commentable.models.agent'));
    }

    public function commentable(): BelongsTo
    {
        return $this->morphTo();
    }

    public function reply(string $comment, array $extraAttributes = []): Model
    {
        return $this->addComment($comment, $extraAttributes);
    }

    public function replyFrom(CanCommentInterface $agent, string $comment, array $extraAttributes = []): Model
    {
        return $this->addCommentFrom($agent, $comment, $extraAttributes);
    }

    public function scopeWithAveragePositiveReactions(Builder $query): Builder
    {
        return $query
            ->select([
                'comments.*',
                DB::raw(sprintf(
                    'SUM(CASE when %s.type = "%s" then 1 when %s.type = "%s" then -1 else 0 END) as average_positive_reactions',
                    config('laravel-commentable.tables.comment_reaction'),
                    CommentReactionTypeEnum::Like->value,
                    config('laravel-commentable.tables.comment_reaction'),
                    CommentReactionTypeEnum::Dislike->value
                )),
            ])
            ->join(config('laravel-commentable.tables.comment_reaction'), config('laravel-commentable.tables.comment_reaction').'.comment_id', '=', config('laravel-commentable.tables.comments').'.id')
            ->groupBy(config('laravel-commentable.tables.comments').'.id');
    }

    public function scopePopular(Builder $query): Builder
    {
        return $this->scopeWithAveragePositiveReactions($query)->orderBy('average_positive_reactions', 'desc')->latest();
    }

    public function scopeUnpopular(Builder $query): Builder
    {
        return $this->scopeWithAveragePositiveReactions($query)->orderBy('average_positive_reactions', 'asc')->latest();
    }
}
