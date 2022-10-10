<?php

namespace Tests\Fixtures\Models;

use Alfonsobries\LaravelCommentable\Traits\CanComment;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use CanComment;
}
