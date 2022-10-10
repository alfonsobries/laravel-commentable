<?php

namespace Alfonsobries\LaravelCommentable\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class CommentDeleted
{
    use SerializesModels;

    public function __construct(public Model $comment)
    {
    }
}
