<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Contracts;

use Alfonsobries\LaravelCommentable\Contracts\HasComments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface CanComment
{
    public function comment(HasComments $commentable, string $comment): Model;

    public function comments(): HasMany;

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey();
}
