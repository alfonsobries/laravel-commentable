<?php

namespace Alfonsobries\LaravelCommentable\Events;

use Alfonsobries\LaravelCommentable\Models\Comment;
use Illuminate\Queue\SerializesModels;

class CommentUpdated
{
    use SerializesModels;

    public function __construct(public Comment $comment)
    {
    }
}
