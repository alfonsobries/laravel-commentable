<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface CanCommentInterface
{
    public function comment(CommentableInterface $commentable, string $comment): Model;

    public function comments(): HasMany;

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey();
}
