<?php

namespace Alfonsobries\LaravelCommentable\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class CommentDeleting
{
    use SerializesModels;

    public function __construct(public Model $comment)
    {
    }
}
