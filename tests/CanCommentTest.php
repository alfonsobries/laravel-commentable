<?php

declare(strict_types=1);

use Alfonsobries\LaravelCommentable\Models\Comment;
use Tests\Fixtures\Models\Agent;
use Tests\Fixtures\Models\Commentable;

test('an agent can comment into a commentable model', function () {
    $agent = Agent::create();

    $commentable = Commentable::create();

    $comment = $agent->comment($commentable, 'This is a comment');

    expect($comment)->toBeInstanceOf(Comment::class);
});

test('an agent have many comments', function () {
    $agent = Agent::create();

    $commentable = Commentable::create();

    $agent->comment($commentable, 'This is a comment');
    $agent->comment($commentable, 'This is another comment');

    expect($agent->comments()->count())->toBe(2);
});
