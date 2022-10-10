<?php

namespace Tests\Fixtures\Models;

use Alfonsobries\LaravelCommentable\Contracts\CanCommentInterface;
use Illuminate\Database\Eloquent\Model;
use Alfonsobries\LaravelCommentable\Traits\CanComment;

class Agent extends Model implements CanCommentInterface
{
    use CanComment;
}
