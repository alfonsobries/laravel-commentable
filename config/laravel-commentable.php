<?php

declare(strict_types=1);

return [
    'models' => [
        'comment' => \Alfonsobries\LaravelCommentable\Models\Comment::class,
        'comment_reaction' => \Alfonsobries\LaravelCommentable\Models\CommentReaction::class,
        'agent' => "App\Models\User",
    ],
    'tables' => [
        'comments' => 'comments',
        'comment_reaction' => 'comment_reaction',
    ],
];
