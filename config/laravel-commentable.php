<?php

declare(strict_types=1);

return [
    'models' => [
        'comment' => \Alfonsobries\LaravelCommentable\Models\Comment::class,
        'agent' => "App\Models\User",
    ],
    'tables' => [
        'comments' => 'comments',
    ],
];
