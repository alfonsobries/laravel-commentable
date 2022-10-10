<?php

namespace Tests\Fixtures\Models;

use Alfonsobries\LaravelCommentable\Contracts\CanComment;
use Alfonsobries\LaravelCommentable\Traits\CanCommentTrait;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model implements CanComment
{
    use CanCommentTrait;
}
