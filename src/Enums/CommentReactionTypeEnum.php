<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable\Enums;

enum CommentReactionTypeEnum: string
{
    case Like    = 'like';
    case Dislike = 'dislike';
}
