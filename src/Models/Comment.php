<?php

namespace Alfonsobries\LaravelCommentable\Models;

use Alfonsobries\LaravelCommentable\Contracts\CanCommentInterface;
use Alfonsobries\LaravelCommentable\Contracts\CommentableInterface;
use Alfonsobries\LaravelCommentable\Traits\Approvable;
use Alfonsobries\LaravelCommentable\Traits\Commentable;
use Alfonsobries\LaravelCommentable\Traits\HasCommentEvents;
use Alfonsobries\LaravelCommentable\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model implements CommentableInterface
{
    use UsesUuid;
    use HasCommentEvents;
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
}
