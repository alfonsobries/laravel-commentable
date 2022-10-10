<?php

namespace Tests\Fixtures\Models;

use Alfonsobries\LaravelCommentable\Contracts\CommentableInterface;
use Alfonsobries\LaravelCommentable\Traits\Commentable as CommentableTrait;
use Illuminate\Database\Eloquent\Model;

class Commentable extends Model implements CommentableInterface
{
    use CommentableTrait;
}
