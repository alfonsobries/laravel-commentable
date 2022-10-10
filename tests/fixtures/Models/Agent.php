<?php

declare(strict_types=1);

namespace Tests\Fixtures\Models;

use Alfonsobries\LaravelCommentable\Contracts\CanCommentInterface;
use Alfonsobries\LaravelCommentable\Traits\CanComment;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model implements CanCommentInterface
{
    use CanComment;
}
