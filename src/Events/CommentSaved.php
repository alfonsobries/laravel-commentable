<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class CommentSaved
{
    use SerializesModels;

    public function __construct(public Model $comment)
    {
    }
}
