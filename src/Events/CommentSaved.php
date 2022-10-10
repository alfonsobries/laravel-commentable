<?php

namespace Alfonsobries\LaravelCommentable\Events;

use Alfonsobries\LaravelCommentable\Models\Comment;
use Illuminate\Queue\SerializesModels;

class CommentSaved
{
    use SerializesModels;

    public function __construct(public Comment $comment)
    {
    }
}
