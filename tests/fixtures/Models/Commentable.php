<?php

namespace Tests\Fixtures\Models;

use Alfonsobries\LaravelCommentable\Contracts\HasComments;
use Alfonsobries\LaravelCommentable\Traits\Commentable as CommentableTrait;
use Illuminate\Database\Eloquent\Model;

class Commentable extends Model implements HasComments
{
    use CommentableTrait;
}
