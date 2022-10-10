<?php

namespace Alfonsobries\LaravelCommentable\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class CommentUpdating
{
    use SerializesModels;

    public function __construct(public Model $comment)
    {
    }
}
