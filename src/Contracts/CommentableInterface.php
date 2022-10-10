<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface CommentableInterface
{
    public function comments(): MorphMany;

    public function addComment(string $comment): Model;
}
